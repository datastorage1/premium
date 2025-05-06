# Digital Login Backend Server

This is the backend server for the Digital Login application. It provides API endpoints for user authentication, registration, password reset functionality, and file management.

## Prerequisites

- Node.js (v14 or later)

## Installation

1. Install the required dependencies:

```bash
cd server
npm install
```

## Running the Server

Development mode (with auto-restart):

```bash
npm run dev
```

Production mode:

```bash
npm start
```

## API Endpoints

### Authentication
- `POST /api/register` - Register a new user
- `POST /api/login` - Authenticate a user
- `POST /api/verify-identity` - Verify user identity for password reset
- `POST /api/reset-password` - Reset user password

### File Management
- `POST /api/upload` - Upload a new file (requires authentication)
- `GET /api/files/:userId` - Get all files for a specific user
- `DELETE /api/files/:fileId` - Delete a specific file

## File Storage

The server stores uploaded files in the `uploads` directory which is automatically created if it doesn't exist. Files can be accessed through the `/uploads/:filename` route.

## Data Storage

The application uses a file-based storage approach:
- User data is stored in `data/users.json`
- File metadata is stored in `data/files.json`

Both directories and files are created automatically when the server first runs.

## Frontend Integration

Update the `API_URL` constant in the frontend's `script.js` file to point to your server address. 