<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BG Remover Pro | Remove Backgrounds Like Magic</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #6C63FF;
            --primary-dark: #5651d4;
            --secondary: #FF6584;
            --dark: #2A2A3C;
            --light: #F8F9FD;
            --success: #4CAF50;
            --card-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            min-height: 100vh;
            padding: 20px;
            overflow-x: hidden;
        }

        /* Header styles */
        header {
            text-align: center;
            padding: 30px 0;
            animation: fadeIn 1s ease;
        }

        .logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .logo i {
            font-size: 2.8rem;
            color: var(--primary);
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo h1 {
            font-size: 2.8rem;
            font-weight: 800;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .tagline {
            color: var(--dark);
            font-size: 1.2rem;
            max-width: 600px;
            margin: 0 auto;
            opacity: 0.8;
        }

        /* Main container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        @media (min-width: 992px) {
            .container {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Upload section */
        .upload-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            gap: 25px;
            animation: slideUp 0.8s ease;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--dark);
            font-size: 1.5rem;
            font-weight: 600;
        }

        .section-title i {
            color: var(--primary);
        }

        .drop-area {
            border: 3px dashed #d1d1e0;
            border-radius: 15px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            background: var(--light);
            overflow: hidden;
        }

        .drop-area:hover, .drop-area.active {
            border-color: var(--primary);
            background: rgba(108, 99, 255, 0.05);
        }

        .drop-area::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: conic-gradient(transparent, var(--primary), transparent 30%);
            animation: rotate 4s linear infinite;
            z-index: 0;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .drop-area.active::before {
            opacity: 0.2;
        }

        .drop-area i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: var(--primary);
            position: relative;
            z-index: 1;
        }

        .drop-area h3 {
            font-size: 1.4rem;
            color: var(--dark);
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .drop-area p {
            color: #777;
            margin-bottom: 20px;
            position: relative;
            z-index: 1;
        }

        .browse-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }

        .browse-btn:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.4);
        }

        .file-input {
            display: none;
        }

        .formats {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .format {
            background: rgba(108, 99, 255, 0.1);
            color: var(--primary);
            padding: 8px 15px;
            border-radius: 30px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        /* Result section */
        .result-section {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            display: flex;
            flex-direction: column;
            gap: 25px;
            animation: slideUp 1s ease;
        }

        .preview-container {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        @media (min-width: 768px) {
            .preview-container {
                grid-template-columns: 1fr 1fr;
            }
        }

        .preview-box {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            position: relative;
            background: var(--light);
            min-height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-box img {
            max-width: 100%;
            max-height: 300px;
            display: none;
        }

        .preview-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(42, 42, 60, 0.8);
            color: white;
            padding: 10px;
            text-align: center;
            font-size: 0.9rem;
        }

        .placeholder {
            text-align: center;
            color: #999;
        }

        .placeholder i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #d1d1e0;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .action-btn {
            flex: 1;
            min-width: 150px;
            padding: 15px;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .action-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .process-btn {
            background: var(--primary);
            color: white;
            box-shadow: 0 5px 15px rgba(108, 99, 255, 0.3);
        }

        .process-btn:hover:not(:disabled) {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.4);
        }

        .download-btn {
            background: var(--success);
            color: white;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
        }

        .download-btn:hover {
            background: #43a047;
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
        }

        /* Loading spinner */
        .loader {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(108, 99, 255, 0.2);
            border-radius: 50%;
            border-top-color: var(--primary);
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        .loader p {
            color: var(--dark);
            font-weight: 500;
        }

        /* Features section */
        .features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .feature-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            text-align: center;
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        }

        .feature-card i {
            font-size: 2.5rem;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            color: var(--dark);
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #777;
            font-size: 0.95rem;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 40px 0 20px;
            color: #777;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { 
                opacity: 0;
                transform: translateY(30px);
            }
            to { 
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Pulse animation for process button */
        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(108, 99, 255, 0.7); }
            70% { box-shadow: 0 0 0 15px rgba(108, 99, 255, 0); }
            100% { box-shadow: 0 0 0 0 rgba(108, 99, 255, 0); }
        }

        /* Instructions */
        .instructions {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: var(--card-shadow);
            margin-top: 30px;
            animation: slideUp 1.2s ease;
        }

        .instructions h2 {
            color: var(--dark);
            margin-bottom: 20px;
            text-align: center;
        }

        .steps {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
        }

        .step {
            flex: 1;
            min-width: 200px;
            text-align: center;
            padding: 20px;
            border-radius: 15px;
            background: var(--light);
        }

        .step-number {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin: 0 auto 15px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .logo h1 {
                font-size: 2.2rem;
            }
            
            .tagline {
                font-size: 1rem;
            }
            
            .upload-section, .result-section {
                padding: 20px;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }

        /* Background Color Controls */
        .bg-controls {
            background: white;
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: var(--card-shadow);
        }

        .bg-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .bg-option {
            width: 100%;
            height: 40px;
            border-radius: 8px;
            border: 2px solid transparent;
            cursor: pointer;
            transition: var(--transition);
        }

        .bg-option:hover {
            transform: scale(1.05);
        }

        .bg-option.active {
            border-color: var(--primary);
        }

        .bg-option.transparent {
            background: linear-gradient(45deg, #ccc 25%, transparent 25%),
                        linear-gradient(-45deg, #ccc 25%, transparent 25%),
                        linear-gradient(45deg, transparent 75%, #ccc 75%),
                        linear-gradient(-45deg, transparent 75%, #ccc 75%);
            background-size: 20px 20px;
            background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
        }

        .bg-option.white { background-color: #ffffff; }
        .bg-option.black { background-color: #000000; }
        .bg-option.red { background-color: #ff0000; }
        .bg-option.green { background-color: #00ff00; }
        .bg-option.blue { background-color: #0000ff; }
        .bg-option.yellow { background-color: #ffff00; }
        .bg-option.purple { background-color: #800080; }
        .bg-option.orange { background-color: #ffa500; }

        .custom-color {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 15px;
        }

        .custom-color input[type="color"] {
            width: 50px;
            height: 50px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }

        .custom-color button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
        }

        .custom-color button:hover {
            background: var(--primary-dark);
        }

        /* Additional Features */
        .additional-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 20px;
        }

        .feature-control {
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: var(--card-shadow);
        }

        .feature-control label {
            display: block;
            margin-bottom: 10px;
            color: var(--dark);
            font-weight: 500;
        }

        .feature-control select,
        .feature-control input[type="range"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 5px;
        }

        .feature-control input[type="range"] {
            -webkit-appearance: none;
            height: 6px;
            background: #ddd;
            border-radius: 3px;
            outline: none;
        }

        .feature-control input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 18px;
            height: 18px;
            background: var(--primary);
            border-radius: 50%;
            cursor: pointer;
        }

        .feature-value {
            text-align: center;
            margin-top: 5px;
            color: var(--dark);
            font-size: 0.9rem;
        }

        /* Custom Background Image Controls */
        .custom-bg-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .custom-bg-upload {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
        }

        .custom-bg-upload input[type="file"] {
            display: none;
        }

        .custom-bg-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .custom-bg-btn:hover {
            background: var(--primary-dark);
        }

        .custom-bg-preview {
            width: 100px;
            height: 100px;
            border-radius: 8px;
            overflow: hidden;
            display: none;
            position: relative;
        }

        .custom-bg-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .remove-bg-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ff4444;
            font-size: 14px;
            transition: var(--transition);
        }

        .remove-bg-btn:hover {
            background: #ff4444;
            color: white;
        }

        .bg-adjustments {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .bg-adjustment {
            background: var(--light);
            padding: 15px;
            border-radius: 8px;
        }

        .bg-adjustment label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark);
            font-weight: 500;
        }

        .bg-adjustment input[type="range"] {
            width: 100%;
        }

        .bg-adjustment select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 6px;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <i class="fas fa-magic"></i>
            <h1>BG Remover Pro</h1>
        </div>
        <p class="tagline">Remove image backgrounds instantly with AI-powered precision. Professional quality in seconds.</p>
    </header>

    <div class="container">
        <div class="upload-section">
            <div class="section-title">
                <i class="fas fa-cloud-upload-alt"></i>
                <h2>Upload Image</h2>
            </div>
            <div class="drop-area" id="dropArea">
                <i class="fas fa-images"></i>
                <h3>Drag & Drop your image here</h3>
                <p>or</p>
                <button class="browse-btn">Browse Files</button>
                <input type="file" id="fileInput" class="file-input" accept="image/*">
            </div>
            <div class="formats">
                <div class="format">JPG</div>
                <div class="format">JPEG</div>
                <div class="format">PNG</div>
                <div class="format">WEBP</div>
            </div>
        </div>

        <div class="result-section">
            <div class="section-title">
                <i class="fas fa-image"></i>
                <h2>Result Preview</h2>
            </div>
            <div class="preview-container">
                <div class="preview-box">
                    <div class="placeholder">
                        <i class="fas fa-file-image"></i>
                        <p>Original Image</p>
                    </div>
                    <img id="originalImage" src="" alt="Original Image">
                    <div class="preview-title">Original</div>
                </div>
                <div class="preview-box">
                    <div class="placeholder">
                        <i class="fas fa-wand-magic-sparkles"></i>
                        <p>Background Removed</p>
                    </div>
                    <img id="resultImage" src="" alt="Result Image">
                    <div class="preview-title">Transparent Background</div>
                </div>
            </div>

            <!-- Background Color Controls -->
            <div class="bg-controls">
                <h3>Background Options</h3>
                <div class="bg-options">
                    <div class="bg-option transparent active" data-color="transparent"></div>
                    <div class="bg-option white" data-color="#ffffff"></div>
                    <div class="bg-option black" data-color="#000000"></div>
                    <div class="bg-option red" data-color="#ff0000"></div>
                    <div class="bg-option green" data-color="#00ff00"></div>
                    <div class="bg-option blue" data-color="#0000ff"></div>
                    <div class="bg-option yellow" data-color="#ffff00"></div>
                    <div class="bg-option purple" data-color="#800080"></div>
                    <div class="bg-option orange" data-color="#ffa500"></div>
                </div>
                <div class="custom-color">
                    <input type="color" id="customColor" value="#ffffff">
                    <button id="applyCustomColor">Apply Custom Color</button>
                </div>

                <!-- Custom Background Image Section -->
                <div class="custom-bg-section">
                    <h3>Custom Background Image</h3>
                    <div class="custom-bg-upload">
                        <button class="custom-bg-btn" id="uploadBgBtn">
                            <i class="fas fa-image"></i>
                            Upload Background
                        </button>
                        <input type="file" id="bgImageInput" accept="image/*">
                        <div class="custom-bg-preview" id="bgPreview">
                            <img id="bgPreviewImg" src="" alt="Background Preview">
                            <button class="remove-bg-btn" id="removeBgBtn">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <div class="bg-adjustments">
                        <div class="bg-adjustment">
                            <label for="bgScale">Background Scale</label>
                            <input type="range" id="bgScale" min="50" max="200" value="100">
                        </div>
                        <div class="bg-adjustment">
                            <label for="bgPosition">Background Position</label>
                            <select id="bgPosition">
                                <option value="center">Center</option>
                                <option value="top">Top</option>
                                <option value="bottom">Bottom</option>
                                <option value="left">Left</option>
                                <option value="right">Right</option>
                            </select>
                        </div>
                        <div class="bg-adjustment">
                            <label for="bgBlur">Background Blur</label>
                            <input type="range" id="bgBlur" min="0" max="20" value="0">
                        </div>
                        <div class="bg-adjustment">
                            <label for="bgBrightness">Background Brightness</label>
                            <input type="range" id="bgBrightness" min="0" max="200" value="100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Features -->
            <div class="additional-features">
                <div class="feature-control">
                    <label for="imageSize">Image Size</label>
                    <select id="imageSize">
                        <option value="preview">Preview</option>
                        <option value="full">Full Size</option>
                        <option value="hd">HD</option>
                        <option value="4k">4K</option>
                    </select>
                </div>
                <div class="feature-control">
                    <label for="format">Format</label>
                    <select id="format">
                        <option value="png">PNG</option>
                        <option value="jpg">JPG</option>
                        <option value="zip">ZIP</option>
                    </select>
                </div>
                <div class="feature-control">
                    <label for="edgeRefinement">Edge Refinement</label>
                    <input type="range" id="edgeRefinement" min="0" max="100" value="50">
                    <div class="feature-value">50%</div>
                </div>
                <div class="feature-control">
                    <label for="semiTransparency">Semi-Transparency</label>
                    <select id="semiTransparency">
                        <option value="true">Enabled</option>
                        <option value="false">Disabled</option>
                    </select>
                </div>
            </div>

            <div class="loader" id="loader">
                <div class="spinner"></div>
                <p>Removing background... This may take a few seconds</p>
            </div>
            <div class="action-buttons">
                <button class="action-btn process-btn pulse" id="processBtn" disabled>
                    <i class="fas fa-sparkles"></i>
                    Process Image
                </button>
                <button class="action-btn download-btn" id="downloadBtn" disabled>
                    <i class="fas fa-download"></i>
                    Download Result
                </button>
            </div>
        </div>
    </div>

    <div class="instructions">
        <h2>How It Works</h2>
        <div class="steps">
            <div class="step">
                <div class="step-number">1</div>
                <h3>Upload Image</h3>
                <p>Drag & drop or click to upload your image</p>
            </div>
            <div class="step">
                <div class="step-number">2</div>
                <h3>Process</h3>
                <p>Click the process button to remove background</p>
            </div>
            <div class="step">
                <div class="step-number">3</div>
                <h3>Download</h3>
                <p>Download your image with transparent background</p>
            </div>
        </div>
    </div>

    <div class="features">
        <div class="feature-card">
            <i class="fas fa-bolt"></i>
            <h3>Lightning Fast</h3>
            <p>Remove backgrounds in seconds with our powerful AI technology</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-crown"></i>
            <h3>High Quality</h3>
            <p>Professional-grade results with perfect edges every time</p>
        </div>
        <div class="feature-card">
            <i class="fas fa-shield-alt"></i>
            <h3>Secure & Private</h3>
            <p>Your images are never stored or shared with third parties</p>
        </div>
    </div>

    <footer>
        <p>© 2023 BG Remover Pro. All rights reserved.</p>
        <p>Powered by Remove.bg API</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elements
            const dropArea = document.getElementById('dropArea');
            const fileInput = document.getElementById('fileInput');
            const originalImage = document.getElementById('originalImage');
            const resultImage = document.getElementById('resultImage');
            const processBtn = document.getElementById('processBtn');
            const downloadBtn = document.getElementById('downloadBtn');
            const loader = document.getElementById('loader');
            
            // API configuration
            const API_KEY = '5Kz884DE7ykjrp2hn1nJCM8a'; // Remove hardcoded API key - should be set by user
            const API_URL = 'https://api.remove.bg/v1.0/removebg';
            const MAX_FILE_SIZE = 10 * 1024 * 1024; // 10MB limit
            
            // File handling
            function handleFiles(files) {
                if (files.length === 0) return;
                
                const file = files[0];
                
                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Please select an image file (JPG, JPEG, PNG, or WEBP).');
                    return;
                }
                
                // Validate file size
                if (file.size > MAX_FILE_SIZE) {
                    alert('File size exceeds 10MB limit. Please choose a smaller image.');
                    return;
                }
                
                // Display original image
                const reader = new FileReader();
                reader.onload = function(e) {
                    originalImage.src = e.target.result;
                    originalImage.style.display = 'block';
                    document.querySelector('.preview-box .placeholder').style.display = 'none';
                    
                    // Enable process button
                    processBtn.disabled = false;
                };
                reader.readAsDataURL(file);
            }
            
            // Event listeners
            dropArea.addEventListener('click', () => {
                fileInput.click();
            });
            
            fileInput.addEventListener('change', (e) => {
                handleFiles(e.target.files);
            });
            
            // Drag and drop events
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                dropArea.addEventListener(eventName, highlight, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                dropArea.addEventListener(eventName, unhighlight, false);
            });
            
            function highlight() {
                dropArea.classList.add('active');
            }
            
            function unhighlight() {
                dropArea.classList.remove('active');
            }
            
            dropArea.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                handleFiles(files);
            });
            
            // Background color handling
            const bgOptions = document.querySelectorAll('.bg-option');
            const customColorInput = document.getElementById('customColor');
            const applyCustomColorBtn = document.getElementById('applyCustomColor');
            const resultPreview = document.querySelector('.preview-box:last-child');
            let currentBgColor = 'transparent';

            bgOptions.forEach(option => {
                option.addEventListener('click', () => {
                    // Remove active class from all options
                    bgOptions.forEach(opt => opt.classList.remove('active'));
                    // Add active class to clicked option
                    option.classList.add('active');
                    
                    const color = option.dataset.color;
                    currentBgColor = color;
                    applyBackgroundColor(color);
                });
            });

            applyCustomColorBtn.addEventListener('click', () => {
                const color = customColorInput.value;
                currentBgColor = color;
                applyBackgroundColor(color);
                
                // Update active state
                bgOptions.forEach(opt => opt.classList.remove('active'));
            });

            function applyBackgroundColor(color) {
                if (color === 'transparent') {
                    resultPreview.style.background = 'transparent';
                } else {
                    resultPreview.style.background = color;
                }
                
                // If we have a processed image, update it with the new background
                if (resultImage.src && resultImage.src !== window.location.href) {
                    const img = new Image();
                    img.src = resultImage.src;
                    
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        
                        canvas.width = img.width;
                        canvas.height = img.height;
                        
                        if (color !== 'transparent') {
                            ctx.fillStyle = color;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                        }
                        
                        ctx.drawImage(img, 0, 0);
                        
                        canvas.toBlob(blob => {
                            const newUrl = URL.createObjectURL(blob);
                            resultImage.src = newUrl;
                        }, 'image/png');
                    };
                }
            }

            // Additional features handling
            const imageSize = document.getElementById('imageSize');
            const format = document.getElementById('format');
            const edgeRefinement = document.getElementById('edgeRefinement');
            const semiTransparency = document.getElementById('semiTransparency');
            const edgeRefinementValue = document.querySelector('.feature-value');

            edgeRefinement.addEventListener('input', (e) => {
                edgeRefinementValue.textContent = `${e.target.value}%`;
            });

            // Update process button click handler
            processBtn.addEventListener('click', async () => {
                if (!originalImage.src || originalImage.src === window.location.href) {
                    alert('Please upload an image first.');
                    return;
                }
                
                // Show loader
                loader.style.display = 'block';
                processBtn.disabled = true;
                downloadBtn.disabled = true;
                
                try {
                    // Convert base64 to blob
                    const response = await fetch(originalImage.src);
                    const blob = await response.blob();
                    
                    // Create form data
                    const formData = new FormData();
                    formData.append('image_file', blob);
                    
                    // Add additional parameters
                    formData.append('size', imageSize.value);
                    formData.append('format', format.value);
                    formData.append('edge_refinement', edgeRefinement.value);
                    formData.append('semi_transparency', semiTransparency.value);
                    
                    // Make API request
                    const apiResponse = await fetch(API_URL, {
                        method: 'POST',
                        headers: {
                            'X-Api-Key': API_KEY
                        },
                        body: formData
                    });
                    
                    if (!apiResponse.ok) {
                        throw new Error(`API request failed: ${apiResponse.statusText}`);
                    }
                    
                    // Get the processed image
                    const resultBlob = await apiResponse.blob();
                    const resultUrl = URL.createObjectURL(resultBlob);
                    
                    // Create a temporary image to get dimensions
                    const tempImg = new Image();
                    tempImg.src = resultUrl;
                    
                    await new Promise((resolve) => {
                        tempImg.onload = resolve;
                    });
                    
                    // Set the result image dimensions
                    resultImage.width = tempImg.width;
                    resultImage.height = tempImg.height;
                    
                    // Display result
                    resultImage.src = resultUrl;
                    resultImage.style.display = 'block';
                    document.querySelectorAll('.preview-box .placeholder')[1].style.display = 'none';
                    
                    // If there's a custom background, apply it
                    if (customBgImage) {
                        applyCustomBackground();
                    } else {
                        // Apply selected background color
                        applyBackgroundColor(currentBgColor);
                    }
                    
                    // Enable download button
                    downloadBtn.disabled = false;
                } catch (error) {
                    console.error('Error processing image:', error);
                    alert(`Failed to process image: ${error.message}. Please try again.`);
                } finally {
                    // Hide loader and re-enable process button
                    loader.style.display = 'none';
                    processBtn.disabled = false;
                }
            });

            // Update download button click handler
            downloadBtn.addEventListener('click', () => {
                if (!resultImage.src || resultImage.src === window.location.href) {
                    alert('No result image to download.');
                    return;
                }
                
                // Create a temporary link for download
                const link = document.createElement('a');
                link.href = resultImage.src;
                link.download = `background-removed.${format.value}`;
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            });

            // Custom background image handling
            const uploadBgBtn = document.getElementById('uploadBgBtn');
            const bgImageInput = document.getElementById('bgImageInput');
            const bgPreview = document.getElementById('bgPreview');
            const bgPreviewImg = document.getElementById('bgPreviewImg');
            const removeBgBtn = document.getElementById('removeBgBtn');
            const bgScale = document.getElementById('bgScale');
            const bgPosition = document.getElementById('bgPosition');
            const bgBlur = document.getElementById('bgBlur');
            const bgBrightness = document.getElementById('bgBrightness');
            
            let customBgImage = null;

            uploadBgBtn.addEventListener('click', () => {
                bgImageInput.click();
            });

            bgImageInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        customBgImage = e.target.result;
                        bgPreviewImg.src = customBgImage;
                        bgPreview.style.display = 'block';
                        applyCustomBackground();
                    };
                    reader.readAsDataURL(file);
                }
            });

            removeBgBtn.addEventListener('click', () => {
                customBgImage = null;
                bgPreview.style.display = 'none';
                bgPreviewImg.src = '';
                bgImageInput.value = '';
                applyBackgroundColor(currentBgColor);
            });

            // Background adjustment handlers
            [bgScale, bgPosition, bgBlur, bgBrightness].forEach(control => {
                control.addEventListener('change', applyCustomBackground);
            });

            function applyCustomBackground() {
                if (!customBgImage) return;

                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const img = new Image();
                
                img.onload = () => {
                    // Set canvas size to match the result image
                    canvas.width = resultImage.width;
                    canvas.height = resultImage.height;

                    // Clear the canvas
                    ctx.clearRect(0, 0, canvas.width, canvas.height);

                    // Calculate scaled dimensions while maintaining aspect ratio
                    const scale = bgScale.value / 100;
                    let scaledWidth = img.width * scale;
                    let scaledHeight = img.height * scale;

                    // Calculate aspect ratios
                    const canvasRatio = canvas.width / canvas.height;
                    const imageRatio = scaledWidth / scaledHeight;

                    // Adjust dimensions to cover the canvas while maintaining aspect ratio
                    if (imageRatio > canvasRatio) {
                        scaledHeight = canvas.height;
                        scaledWidth = scaledHeight * imageRatio;
                    } else {
                        scaledWidth = canvas.width;
                        scaledHeight = scaledWidth / imageRatio;
                    }

                    // Calculate position
                    let x = 0, y = 0;
                    switch(bgPosition.value) {
                        case 'center':
                            x = (canvas.width - scaledWidth) / 2;
                            y = (canvas.height - scaledHeight) / 2;
                            break;
                        case 'top':
                            x = (canvas.width - scaledWidth) / 2;
                            y = 0;
                            break;
                        case 'bottom':
                            x = (canvas.width - scaledWidth) / 2;
                            y = canvas.height - scaledHeight;
                            break;
                        case 'left':
                            x = 0;
                            y = (canvas.height - scaledHeight) / 2;
                            break;
                        case 'right':
                            x = canvas.width - scaledWidth;
                            y = (canvas.height - scaledHeight) / 2;
                            break;
                    }

                    // Create a temporary canvas for the background
                    const bgCanvas = document.createElement('canvas');
                    const bgCtx = bgCanvas.getContext('2d');
                    bgCanvas.width = canvas.width;
                    bgCanvas.height = canvas.height;

                    // Draw and apply effects to background
                    bgCtx.filter = `blur(${bgBlur.value}px) brightness(${bgBrightness.value}%)`;
                    bgCtx.drawImage(img, x, y, scaledWidth, scaledHeight);

                    // Draw the background onto the main canvas
                    ctx.drawImage(bgCanvas, 0, 0);

                    // Create a temporary canvas for the subject
                    const subjectCanvas = document.createElement('canvas');
                    const subjectCtx = subjectCanvas.getContext('2d');
                    subjectCanvas.width = canvas.width;
                    subjectCanvas.height = canvas.height;

                    // Draw the transparent image
                    subjectCtx.drawImage(resultImage, 0, 0);

                    // Get the image data
                    const imageData = subjectCtx.getImageData(0, 0, subjectCanvas.width, subjectCanvas.height);
                    const data = imageData.data;

                    // Process the image data to preserve transparency
                    for (let i = 0; i < data.length; i += 4) {
                        // If the pixel is not transparent
                        if (data[i + 3] > 0) {
                            // Draw the pixel from the subject onto the main canvas
                            ctx.putImageData(imageData, 0, 0);
                            break;
                        }
                    }

                    // Update the result image
                    canvas.toBlob(blob => {
                        const newUrl = URL.createObjectURL(blob);
                        resultImage.src = newUrl;
                    }, 'image/png');
                };
                
                img.src = customBgImage;
            }

            // Update the applyBackgroundColor function
            function applyBackgroundColor(color) {
                if (customBgImage) {
                    applyCustomBackground();
                    return;
                }

                if (color === 'transparent') {
                    resultPreview.style.background = 'transparent';
                } else {
                    resultPreview.style.background = color;
                }
                
                if (resultImage.src && resultImage.src !== window.location.href) {
                    const img = new Image();
                    img.src = resultImage.src;
                    
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');
                        
                        canvas.width = img.width;
                        canvas.height = img.height;
                        
                        if (color !== 'transparent') {
                            ctx.fillStyle = color;
                            ctx.fillRect(0, 0, canvas.width, canvas.height);
                        }
                        
                        ctx.drawImage(img, 0, 0);
                        
                        canvas.toBlob(blob => {
                            const newUrl = URL.createObjectURL(blob);
                            resultImage.src = newUrl;
                        }, 'image/png');
                    };
                }
            }
        });
    </script>
</body>
</html>