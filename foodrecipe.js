const formContainer = document.querySelector('.form-container');
const openFormButton = document.querySelector('#form-open');
const closeFormButton = document.querySelector('.form_close');
const formOverlay = document.querySelector('.form-overlay');
const loginForm = document.querySelector('.login-form');
const signupForm = document.querySelector('.signup-form');
const showSignup = document.querySelector('.show-signup');
const showLogin = document.querySelector('.show-login');

// Function to open the form and show the overlay
openFormButton.addEventListener('click', () => {
    formContainer.classList.add('active');  // Show form
    formOverlay.classList.add('active');    // Show overlay
});

// Function to close the form and hide the overlay
closeFormButton.addEventListener('click', () => {
    formContainer.classList.remove('active');  // Hide form
    formOverlay.classList.remove('active');    // Hide overlay
});

// Close form when clicking on the overlay
formOverlay.addEventListener('click', () => {
    formContainer.classList.remove('active');  // Hide form
    formOverlay.classList.remove('active');    // Hide overlay
});

// Prevent closing when clicking inside the form container itself
formContainer.addEventListener('click', (e) => {
    e.stopPropagation();  // Prevent click event from propagating to the overlay
});

showSignup.addEventListener('click', function (e) {
    e.preventDefault();
    loginForm.style.display = 'none';  // Hide login form
    signupForm.style.display = 'block'; // Show signup form
});

showLogin.addEventListener('click', function (e) {
    e.preventDefault();
    signupForm.style.display = 'none'; // Hide signup form
    loginForm.style.display = 'block'; // Show login form
});

function validatePasswords() {
    const password = document.getElementById("signup-create-password").value;
    const confirmPassword = document.getElementById("signup-confirm-password").value;
    const errorMessage = document.getElementById("password-error");

    if (password !== confirmPassword) {
        errorMessage.style.display = "block"; // Show error message
        return false; // Prevent form submission
    } else {
        errorMessage.style.display = "none"; // Hide error message
        return true; // Allow form submission
    }
}

function togglePassword(fieldId, icon) {
    const passwordField = document.getElementById(fieldId);

    // Toggle the type attribute between "password" and "text"
    if (passwordField.type === 'password') {
        passwordField.type = 'text'; // Show password
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    } else {
        passwordField.type = 'password'; // Hide password
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    }
}

const ingredientSearch = document.getElementById('ingredientSearch');
const suggestions = document.getElementById('suggestions');
const apiKey = "<?php echo $apiKey; ?>"; // Spoonacular API key

// Show suggestions based on input
ingredientSearch.addEventListener('input', () => {
    const query = ingredientSearch.value;

    if (query.length > 1) { // Start searching after a few letters
        fetch(`https://api.spoonacular.com/food/ingredients/autocomplete?query=${query}&number=10&apiKey=${apiKey}`)
            .then(response => response.json())
            .then(data => {
                suggestions.innerHTML = ''; // Clear previous suggestions
                data.forEach(item => {
                    const suggestionItem = document.createElement('div');
                    suggestionItem.classList.add('autocomplete-suggestion');
                    suggestionItem.textContent = item.name;

                    suggestionItem.addEventListener('click', () => {
                        ingredientSearch.value = item.name;
                        suggestions.innerHTML = ''; // Clear suggestions
                    });

                    suggestions.appendChild(suggestionItem);
                });
            })
            .catch(error => console.error('Error fetching suggestions:', error));
    } else {
        suggestions.innerHTML = ''; // Clear suggestions if input is too short
    }
});

// Hide suggestions when clicking outside the input
document.addEventListener('click', (event) => {
    if (!ingredientSearch.contains(event.target) && !suggestions.contains(event.target)) {
        suggestions.innerHTML = '';
    }
});


// View Recipe pop-up toh bossinggggggz
function viewRecipeDetails(recipeId) {
    const apiKey = "<?php echo $apiKey; ?>";

    fetch(`https://api.spoonacular.com/recipes/${recipeId}/information?apiKey=${apiKey}`)
        .then(response => response.json())
        .then(recipe => {
            // Update modal content
            document.getElementById("recipeTitle").textContent = recipe.title;
            document.getElementById("recipeImage").src = recipe.image;

            // Clear old ingredients
            const ingredientsList = document.getElementById("recipeIngredients");
            ingredientsList.innerHTML = '';

            // Add ingredients
            recipe.extendedIngredients.forEach(ingredient => {
                const li = document.createElement('li');
                li.textContent = ingredient.original;
                ingredientsList.appendChild(li);
            });

            // Check if instructions are structured (i.e., already contains <ol><li>)
            const instructions = recipe.instructions || "No instructions provided.";
            const instructionsList = document.createElement('ol');

            // Clean up instructions if they contain dual numbering (e.g., "1.1.", "2.2.")
            const cleanedInstructions = instructions.replace(/\d+\.\d+\./g, match => {
                return match.split('.')[0] + '.';
            });

            // Split cleaned instructions into steps and add them to an ordered list
            const instructionSteps = cleanedInstructions.split('.');  // Split by periods (or any other delimiter you prefer)
            instructionSteps.forEach(step => {
                if (step.trim()) {  // Skip empty strings
                    const li = document.createElement('li');
                    li.textContent = step.trim();
                    instructionsList.appendChild(li);
                }
            });

            document.getElementById("recipeInstructions").innerHTML = '';  // Clear previous instructions
            document.getElementById("recipeInstructions").appendChild(instructionsList);  // Add the numbered list

            // Show modal
            const modal = document.getElementById("recipeModal");
            modal.style.display = "block";
        })
        .catch(error => console.error('Error fetching recipe details:', error));
}



// Close Modal Function
function closeModal() {
    const modal = document.getElementById("recipeModal");
    modal.style.display = "none";
}

// Event Listener for Close Button
document.querySelector(".close-btn").addEventListener("click", closeModal);

// Close Modal on Outside Click
window.onclick = function (event) {
    const modal = document.getElementById("recipeModal");
    if (event.target === modal) {
        closeModal();
    }
};
