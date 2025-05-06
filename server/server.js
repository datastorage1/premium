const express = require('express');
const cors = require('cors');
const bcrypt = require('bcrypt');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

// Initialize Express app
const app = express();
const PORT = process.env.PORT || 5000;

// Middleware
app.use(cors());
app.use(express.json());
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Create necessary directories if they don't exist
const uploadsDir = path.join(__dirname, 'uploads');
const dataDir = path.join(__dirname, 'data');
if (!fs.existsSync(uploadsDir)) {
    fs.mkdirSync(uploadsDir, { recursive: true });
}
if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir, { recursive: true });
}

// File paths for data storage
const USERS_FILE = path.join(dataDir, 'users.json');
const FILES_FILE = path.join(dataDir, 'files.json');

// Initialize data files if they don't exist
if (!fs.existsSync(USERS_FILE)) {
    fs.writeFileSync(USERS_FILE, JSON.stringify([]));
}
if (!fs.existsSync(FILES_FILE)) {
    fs.writeFileSync(FILES_FILE, JSON.stringify([]));
}

// Helper functions for data management
function readUsers() {
    const data = fs.readFileSync(USERS_FILE, 'utf8');
    return JSON.parse(data);
}

function writeUsers(users) {
    fs.writeFileSync(USERS_FILE, JSON.stringify(users, null, 2));
}

function readFiles() {
    const data = fs.readFileSync(FILES_FILE, 'utf8');
    return JSON.parse(data);
}

function writeFiles(files) {
    fs.writeFileSync(FILES_FILE, JSON.stringify(files, null, 2));
}

function generateId() {
    return Date.now().toString() + Math.random().toString(36).substr(2, 9);
}

// Configure multer for file uploads
const storage = multer.diskStorage({
    destination: function (req, file, cb) {
        cb(null, uploadsDir);
    },
    filename: function (req, file, cb) {
        const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
        cb(null, uniqueSuffix + '-' + file.originalname);
    }
});

const upload = multer({ 
    storage: storage,
    limits: { fileSize: 10 * 1024 * 1024 } // 10MB limit
});

// Health check endpoint
app.get('/api/health', (req, res) => {
    res.status(200).json({ status: 'ok', message: 'Server is running' });
});

// Routes
// Register User
app.post('/api/register', async (req, res) => {
    try {
        const { fullName, email, dob, password } = req.body;
        
        // Validate input
        if (!fullName || !email || !dob || !password) {
            return res.status(400).json({ message: 'All fields are required' });
        }
        
        // Check if user already exists
        const users = readUsers();
        const existingUser = users.find(user => user.email === email);
        if (existingUser) {
            return res.status(400).json({ message: 'User already exists' });
        }
        
        // Hash password
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(password, salt);
        
        // Create new user
        const newUser = {
            id: generateId(),
            fullName,
            email,
            dob,
            password: hashedPassword,
            createdAt: new Date().toISOString()
        };
        
        // Save user
        users.push(newUser);
        writeUsers(users);
        
        res.status(201).json({ message: 'User registered successfully' });
    } catch (error) {
        console.error('Registration error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Login User
app.post('/api/login', async (req, res) => {
    try {
        const { email, password } = req.body;
        
        // Validate input
        if (!email || !password) {
            return res.status(400).json({ message: 'Email and password are required' });
        }
        
        // Check if user exists
        const users = readUsers();
        const user = users.find(user => user.email === email);
        if (!user) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }
        
        // Validate password
        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }
        
        // Return user data without password
        const userData = {
            id: user.id,
            fullName: user.fullName,
            email: user.email,
            dob: user.dob
        };
        
        res.status(200).json({ message: 'Login successful', user: userData });
    } catch (error) {
        console.error('Login error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Verify identity for password reset
app.post('/api/verify-identity', async (req, res) => {
    try {
        const { email, dob } = req.body;
        
        // Validate input
        if (!email || !dob) {
            return res.status(400).json({ message: 'Email and date of birth are required' });
        }
        
        // Find user by email and dob
        const users = readUsers();
        const user = users.find(user => user.email === email && user.dob === dob);
        if (!user) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }
        
        res.status(200).json({ message: 'Identity verified', userId: user.id });
    } catch (error) {
        console.error('Verification error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Reset password
app.post('/api/reset-password', async (req, res) => {
    try {
        const { email, newPassword } = req.body;
        
        // Validate input
        if (!email || !newPassword) {
            return res.status(400).json({ message: 'Email and new password are required' });
        }
        
        // Get users
        const users = readUsers();
        const userIndex = users.findIndex(user => user.email === email);
        
        if (userIndex === -1) {
            return res.status(400).json({ message: 'User not found' });
        }
        
        // Hash the new password
        const salt = await bcrypt.genSalt(10);
        const hashedPassword = await bcrypt.hash(newPassword, salt);
        
        // Update user's password
        users[userIndex].password = hashedPassword;
        writeUsers(users);
        
        res.status(200).json({ message: 'Password reset successful' });
    } catch (error) {
        console.error('Password reset error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// File Upload
app.post('/api/upload', upload.single('file'), async (req, res) => {
    try {
        if (!req.file) {
            return res.status(400).json({ message: 'No file uploaded' });
        }

        const userId = req.body.userId;
        const fileName = req.body.fileName || req.file.originalname;
        const fileDescription = req.body.fileDescription || '';
        
        if (!userId) {
            return res.status(400).json({ message: 'User ID is required' });
        }

        // Check if user exists
        const users = readUsers();
        const userExists = users.some(user => user.id === userId);
        if (!userExists) {
            return res.status(400).json({ message: 'User not found' });
        }

        // Get existing files
        const files = readFiles();
        
        // Create new file record
        const newFile = {
            id: generateId(),
            userId: userId,
            filename: req.file.filename,
            originalName: req.file.originalname,
            displayName: fileName,
            description: fileDescription,
            filePath: req.file.path,
            fileSize: req.file.size,
            fileType: req.file.mimetype,
            uploadDate: new Date().toISOString()
        };

        // Save file record
        files.push(newFile);
        writeFiles(files);
        
        res.status(201).json({ 
            message: 'File uploaded successfully',
            file: {
                id: newFile.id,
                filename: newFile.filename,
                originalName: newFile.originalName,
                displayName: newFile.displayName,
                description: newFile.description,
                fileSize: newFile.fileSize,
                fileType: newFile.fileType,
                uploadDate: newFile.uploadDate
            }
        });
    } catch (error) {
        console.error('File upload error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Get user files
app.get('/api/files/:userId', async (req, res) => {
    try {
        const userId = req.params.userId;
        
        // Check if user exists
        const users = readUsers();
        const userExists = users.some(user => user.id === userId);
        if (!userExists) {
            return res.status(400).json({ message: 'User not found' });
        }
        
        // Get user files
        const files = readFiles();
        const userFiles = files.filter(file => file.userId === userId);
        
        const fileList = userFiles.map(file => ({
            id: file.id,
            filename: file.filename,
            originalName: file.originalName,
            displayName: file.displayName,
            description: file.description,
            fileSize: file.fileSize,
            fileType: file.fileType,
            uploadDate: file.uploadDate,
            url: `/uploads/${file.filename}`
        }));
        
        res.status(200).json({ files: fileList });
    } catch (error) {
        console.error('Get files error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Delete file
app.delete('/api/files/:fileId', async (req, res) => {
    try {
        const fileId = req.params.fileId;
        
        // Get files
        const files = readFiles();
        const fileIndex = files.findIndex(file => file.id === fileId);
        
        if (fileIndex === -1) {
            return res.status(404).json({ message: 'File not found' });
        }
        
        const file = files[fileIndex];
        
        // Delete file from storage
        const filePath = file.filePath;
        if (fs.existsSync(filePath)) {
            fs.unlinkSync(filePath);
        }
        
        // Delete file record from data
        files.splice(fileIndex, 1);
        writeFiles(files);
        
        res.status(200).json({ message: 'File deleted successfully' });
    } catch (error) {
        console.error('Delete file error:', error);
        res.status(500).json({ message: 'Server error' });
    }
});

// Start server
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
    console.log(`API URL: http://localhost:${PORT}/api`);
}); 