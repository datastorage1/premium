// Welcome music and overlay
const welcomeMusic = document.getElementById('welcome-music');
const welcomeOverlay = document.querySelector('.welcome-overlay');
const backgroundMusic = document.getElementById('background-music');
let isMusicPlaying = false;

// Function to handle audio errors
function handleAudioError(audioElement, errorMessage) {
    console.error(errorMessage);
    // Continue with the animation even if audio fails
    if (audioElement === welcomeMusic) {
        setTimeout(() => {
            welcomeOverlay.style.display = 'none';
        }, 7000);
    }
}

// Play welcome music when page loads
document.addEventListener('DOMContentLoaded', () => {
    // Try to play welcome music
    if (welcomeMusic) {
        welcomeMusic.volume = 0.7; // Set volume to 70%
        const playPromise = welcomeMusic.play();
        
        if (playPromise !== undefined) {
            playPromise
                .then(() => {
                    console.log('Welcome music started playing');
                })
                .catch(error => {
                    handleAudioError(welcomeMusic, 'Error playing welcome music:', error);
                });
        }
    } else {
        console.error('Welcome music element not found');
    }
    
    // Remove welcome overlay after animation
    setTimeout(() => {
        welcomeOverlay.style.display = 'none';
    }, 7000); // 4s delay + 3s animation
});

// Language switching functionality
let currentLanguage = 'en';

function toggleLanguage() {
    currentLanguage = currentLanguage === 'en' ? 'bn' : 'en';
    updateLanguage();
}

function updateLanguage() {
    const elements = document.querySelectorAll('[data-en]');
    elements.forEach(element => {
        const text = element.getAttribute(`data-${currentLanguage}`);
        if (text) {
            element.textContent = text;
        }
    });
}

// Background music control with error handling
document.addEventListener('click', () => {
    if (!isMusicPlaying && backgroundMusic) {
        backgroundMusic.volume = 0.3; // Set volume to 30%
        const playPromise = backgroundMusic.play();
        
        if (playPromise !== undefined) {
            playPromise
                .then(() => {
                    isMusicPlaying = true;
                    console.log('Background music started playing');
                })
                .catch(error => {
                    console.error('Error playing background music:', error);
                });
        }
    }
});

// Add audio error event listeners
if (welcomeMusic) {
    welcomeMusic.addEventListener('error', (e) => {
        handleAudioError(welcomeMusic, 'Error loading welcome music:', e);
    });
}

if (backgroundMusic) {
    backgroundMusic.addEventListener('error', (e) => {
        console.error('Error loading background music:', e);
    });
}

// Enhanced cricket ball animation
function createCricketBall() {
    const ball = document.createElement('div');
    ball.className = 'cricket-ball';
    ball.style.left = Math.random() * window.innerWidth + 'px';
    ball.style.top = Math.random() * window.innerHeight + 'px';
    document.body.appendChild(ball);

    // Add rotation animation
    ball.style.animation = `bounce 2s infinite, rotate ${Math.random() * 2 + 1}s linear infinite`;

    setTimeout(() => {
        ball.remove();
    }, 2000);
}

// Create cricket balls periodically
setInterval(createCricketBall, 3000);

// Scroll animations with enhanced effects
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
            
            // Add sparkle effect
            if (entry.target.classList.contains('title') || 
                entry.target.classList.contains('subtitle') || 
                entry.target.classList.contains('date-badge')) {
                entry.target.classList.add('sparkle');
            }
        }
    });
}, observerOptions);

// Add animation to sections with enhanced effects
document.querySelectorAll('section').forEach(section => {
    section.style.opacity = '0';
    section.style.transform = 'translateY(20px)';
    section.style.transition = 'opacity 0.5s ease-out, transform 0.5s ease-out';
    observer.observe(section);
});

// Enhanced hover effects for info cards
document.querySelectorAll('.info-card').forEach(card => {
    card.addEventListener('mouseenter', () => {
        card.style.transform = 'translateY(-10px)';
        card.style.boxShadow = '0 15px 30px rgba(0,0,0,0.2)';
    });
    
    card.addEventListener('mouseleave', () => {
        card.style.transform = 'translateY(0)';
        card.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
    });
});

// Enhanced click effects for committee members
document.querySelectorAll('.committee-member').forEach(member => {
    member.addEventListener('click', () => {
        member.style.transform = 'scale(1.1)';
        member.style.boxShadow = '0 10px 20px rgba(0,0,0,0.2)';
        setTimeout(() => {
            member.style.transform = 'scale(1)';
            member.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
        }, 200);
    });
});

// Add sparkle effect to important elements
function addSparkleEffect() {
    const elements = document.querySelectorAll('.title, .subtitle, .date-badge');
    elements.forEach(element => {
        element.classList.add('sparkle');
    });
}

// Initialize the page
document.addEventListener('DOMContentLoaded', () => {
    // Set initial language
    updateLanguage();
    
    // Add sparkle effects
    addSparkleEffect();
    
    // Add smooth scrolling
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
}); 