import http.server
import socketserver
import json
import os
import base64
import hashlib
import time
from urllib.parse import parse_qs, urlparse

# Set the port for the server
PORT = 5000
SERVER_HOST = "localhost"

# Create data directory if it doesn't exist
DATA_DIR = os.path.join(os.path.dirname(os.path.abspath(__file__)), "data")
UPLOADS_DIR = os.path.join(os.path.dirname(os.path.abspath(__file__)), "uploads")

if not os.path.exists(DATA_DIR):
    os.makedirs(DATA_DIR)

if not os.path.exists(UPLOADS_DIR):
    os.makedirs(UPLOADS_DIR)

# File paths for data storage
USERS_FILE = os.path.join(DATA_DIR, "users.json")
FILES_FILE = os.path.join(DATA_DIR, "files.json")

# Initialize data files if they don't exist
if not os.path.exists(USERS_FILE):
    with open(USERS_FILE, "w") as f:
        json.dump([], f)

if not os.path.exists(FILES_FILE):
    with open(FILES_FILE, "w") as f:
        json.dump([], f)

# Helper functions for data management
def read_users():
    with open(USERS_FILE, "r") as f:
        return json.load(f)

def write_users(users):
    with open(USERS_FILE, "w") as f:
        json.dump(users, f, indent=2)

def read_files():
    with open(FILES_FILE, "r") as f:
        return json.load(f)

def write_files(files):
    with open(FILES_FILE, "w") as f:
        json.dump(files, f, indent=2)

def generate_id():
    return str(int(time.time() * 1000)) + str(hash(time.time()))

def hash_password(password):
    # This is a simple hash function, not for production use
    return hashlib.sha256(password.encode()).hexdigest()

def verify_password(password, hashed_password):
    # Verify password hash
    return hash_password(password) == hashed_password

class APIHandler(http.server.SimpleHTTPRequestHandler):
    def _set_headers(self, status_code=200, content_type="application/json"):
        self.send_response(status_code)
        self.send_header("Content-type", content_type)
        # Enable CORS for all origins
        self.send_header("Access-Control-Allow-Origin", "*")
        self.send_header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS")
        self.send_header("Access-Control-Allow-Headers", "Content-Type")
        self.end_headers()

    def do_OPTIONS(self):
        self._set_headers()

    def do_GET(self):
        parsed_url = urlparse(self.path)
        path = parsed_url.path

        if path == "/api/health":
            # Health check endpoint
            self._set_headers()
            self.wfile.write(json.dumps({"status": "ok", "message": "Server is running"}).encode())
            return
        
        if path.startswith("/api/files/"):
            # Get user files endpoint
            user_id = path.split("/")[-1]
            files = read_files()
            user_files = [file for file in files if file.get("userId") == user_id]
            
            file_list = []
            for file in user_files:
                file_list.append({
                    "id": file.get("id"),
                    "filename": file.get("filename"),
                    "originalName": file.get("originalName"),
                    "displayName": file.get("displayName"),
                    "description": file.get("description"),
                    "fileSize": file.get("fileSize"),
                    "fileType": file.get("fileType"),
                    "uploadDate": file.get("uploadDate"),
                    "url": f"/uploads/{file.get('filename')}"
                })
            
            self._set_headers()
            self.wfile.write(json.dumps({"files": file_list}).encode())
            return

        if path.startswith("/uploads/"):
            file_name = path.split("/")[-1]
            file_path = os.path.join(UPLOADS_DIR, file_name)
            
            if os.path.exists(file_path):
                with open(file_path, "rb") as f:
                    self._set_headers(content_type="application/octet-stream")
                    self.wfile.write(f.read())
                return
            else:
                self._set_headers(404)
                self.wfile.write(json.dumps({"message": "File not found"}).encode())
                return
                
        # Default handler for static files (HTML, CSS, JS)
        if path == "/":
            path = "/index.html"
            
        # For all other paths, try to serve from parent directory
        try:
            with open(os.path.join("..", path.lstrip("/")), "rb") as f:
                content_type = "text/html"
                if path.endswith(".css"):
                    content_type = "text/css"
                elif path.endswith(".js"):
                    content_type = "application/javascript"
                
                self._set_headers(content_type=content_type)
                self.wfile.write(f.read())
        except FileNotFoundError:
            self._set_headers(404)
            self.wfile.write(json.dumps({"message": f"File {path} not found"}).encode())

    def do_POST(self):
        content_length = int(self.headers.get("Content-Length", 0))
        post_data = self.rfile.read(content_length)
        
        try:
            data = json.loads(post_data)
        except json.JSONDecodeError:
            self._set_headers(400)
            self.wfile.write(json.dumps({"message": "Invalid JSON"}).encode())
            return

        if self.path == "/api/register":
            full_name = data.get("fullName")
            email = data.get("email")
            dob = data.get("dob")
            password = data.get("password")
            
            if not all([full_name, email, dob, password]):
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "All fields are required"}).encode())
                return
            
            users = read_users()
            if any(user.get("email") == email for user in users):
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "User already exists"}).encode())
                return
            
            new_user = {
                "id": generate_id(),
                "fullName": full_name,
                "email": email,
                "dob": dob,
                "password": hash_password(password),
                "createdAt": time.strftime("%Y-%m-%dT%H:%M:%SZ", time.gmtime())
            }
            
            users.append(new_user)
            write_users(users)
            
            self._set_headers(201)
            self.wfile.write(json.dumps({"message": "User registered successfully"}).encode())
            return
            
        elif self.path == "/api/login":
            email = data.get("email")
            password = data.get("password")
            
            if not all([email, password]):
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "Email and password are required"}).encode())
                return
            
            users = read_users()
            user = next((user for user in users if user.get("email") == email), None)
            
            if not user or not verify_password(password, user.get("password")):
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "Invalid credentials"}).encode())
                return
            
            user_data = {
                "id": user.get("id"),
                "fullName": user.get("fullName"),
                "email": user.get("email"),
                "dob": user.get("dob")
            }
            
            self._set_headers()
            self.wfile.write(json.dumps({"message": "Login successful", "user": user_data}).encode())
            return
            
        elif self.path == "/api/verify-identity":
            email = data.get("email")
            dob = data.get("dob")
            
            if not all([email, dob]):
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "Email and date of birth are required"}).encode())
                return
            
            users = read_users()
            user = next((user for user in users if user.get("email") == email and user.get("dob") == dob), None)
            
            if not user:
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "Invalid credentials"}).encode())
                return
            
            self._set_headers()
            self.wfile.write(json.dumps({"message": "Identity verified", "userId": user.get("id")}).encode())
            return
            
        elif self.path == "/api/reset-password":
            email = data.get("email")
            new_password = data.get("newPassword")
            
            if not all([email, new_password]):
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "Email and new password are required"}).encode())
                return
            
            users = read_users()
            user_index = next((i for i, user in enumerate(users) if user.get("email") == email), None)
            
            if user_index is None:
                self._set_headers(400)
                self.wfile.write(json.dumps({"message": "User not found"}).encode())
                return
            
            users[user_index]["password"] = hash_password(new_password)
            write_users(users)
            
            self._set_headers()
            self.wfile.write(json.dumps({"message": "Password reset successful"}).encode())
            return
            
        else:
            self._set_headers(404)
            self.wfile.write(json.dumps({"message": "Endpoint not found"}).encode())

    def do_DELETE(self):
        if self.path.startswith("/api/files/"):
            file_id = self.path.split("/")[-1]
            files = read_files()
            
            file_index = next((i for i, file in enumerate(files) if file.get("id") == file_id), None)
            
            if file_index is None:
                self._set_headers(404)
                self.wfile.write(json.dumps({"message": "File not found"}).encode())
                return
            
            file = files[file_index]
            file_path = os.path.join(UPLOADS_DIR, file.get("filename"))
            
            if os.path.exists(file_path):
                os.remove(file_path)
            
            files.pop(file_index)
            write_files(files)
            
            self._set_headers()
            self.wfile.write(json.dumps({"message": "File deleted successfully"}).encode())
            return
        else:
            self._set_headers(404)
            self.wfile.write(json.dumps({"message": "Endpoint not found"}).encode())

def run():
    print(f"Starting server at http://{SERVER_HOST}:{PORT}")
    httpd = socketserver.TCPServer((SERVER_HOST, PORT), APIHandler)
    try:
        httpd.serve_forever()
    except KeyboardInterrupt:
        print("Server stopped.")
        httpd.server_close()

if __name__ == "__main__":
    run() 