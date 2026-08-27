// 1. ADMIN DASHBOARD: Tab Switcher
window.switchTab = function(tabName) {
    const sections = document.querySelectorAll('.admin-section');
    sections.forEach(function(section) {
        section.style.display = 'none';
    });

    const links = document.querySelectorAll('.admin-nav-link');
    links.forEach(function(link) {
        link.classList.remove('active');
    });

    const targetSection = document.getElementById('sec-' + tabName);
    const targetLink = document.getElementById('nav-' + tabName);
    
    if (targetSection && targetLink) {
        targetSection.style.display = 'block';
        targetLink.classList.add('active');
    }
};



// 2. CONTACT PAGE: Form Validation
window.validateForm = function(event) {
    let isValid = true;
    const form = document.getElementById('contactForm');

    document.querySelectorAll('.error').forEach(div => div.textContent = '');
    
    if (form['salutation'].value.trim() === '') {
        document.getElementById('salutationError').textContent = 'Please select your salutation.';
        isValid = false;
    }
    if (form['name'].value.trim() === '') {
        document.getElementById('nameError').textContent = 'Name is required.';
        isValid = false;
    }
    
    let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (form['email'].value.trim() === '') {
        document.getElementById('emailError').textContent = 'Email is required.';
        isValid = false;
    } else if (!emailPattern.test(form['email'].value)) {
        document.getElementById('emailError').textContent = 'Email is not valid.';
        isValid = false;
    }
    
    if (form['phone'].value.trim() === '') {
        document.getElementById('phoneError').textContent = 'Phone number is required.';
        isValid = false;
    } else if (!/^[\d\s\-\(\)]{10,15}$/.test(form['phone'].value)) {
        document.getElementById('phoneError').textContent = 'Enter a valid phone number (10-15 digits).';
        isValid = false;
    }
    
    let checkboxes = document.querySelectorAll('input[name="enquiry[]"]');
    let isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    if (!isChecked) {
        document.getElementById('enquiryError').textContent = 'Please select at least one enquiry.';
        isValid = false;
    }
    
    if (form['message'].value.trim() === '') {
        document.getElementById('messageError').textContent = 'Message is required.';
        isValid = false;
    }
    
    if (!isValid) { 
        event.preventDefault(); 
    }
    
    return isValid;
};



// 3. FEATURED ITEMS PAGE: Category Filter
window.filterItems = function(category, buttonElement) {
    const buttons = document.querySelectorAll('.filter-btn');
    buttons.forEach(btn => {
        btn.style.backgroundColor = '#ffffff';
        btn.style.color = '#000000';
    });
    
    // Highlight the clicked button
    buttonElement.style.backgroundColor = 'rgb(30, 136, 229)';
    buttonElement.style.color = '#ffffff';

    const cards = document.querySelectorAll('.product-item');
    
    cards.forEach(card => {
        const itemCat = card.getAttribute('data-category');
        
        if (category === 'all' || itemCat === category) {
            card.style.display = 'flex'; // Uses flex because the PHP file uses display: flex for the cards
        } else {
            card.style.display = 'none'; 
        }
    });
};

// Set initial active button color on page load
document.addEventListener("DOMContentLoaded", function() {
    const filterControls = document.querySelector('.filter-controls');
    if (filterControls) {
        const firstButton = filterControls.querySelector('.filter-btn');
        if(firstButton) {
            firstButton.style.backgroundColor = 'rgb(30, 136, 229)';
            firstButton.style.color = '#ffffff';
        }
    }
});



// 4. LISTINGS PAGE: Live AJAX Search
window.searchListings = function() {
    var text = document.getElementById("searchBox").value;
    var ajax = new XMLHttpRequest();

    ajax.open("GET", "search_listings.php?query=" + encodeURIComponent(text), true);
    ajax.send();

    // Update the table
    ajax.onreadystatechange = function() {
        if (ajax.readyState == 4 && ajax.status == 200) {
            document.getElementById("productTableBody").innerHTML = ajax.responseText;
        }
    };
};



// 5. PURCHASE PAGE: Form Validation
window.validatePurchaseForm = function(event) {
    let isValid = true;
    
    document.getElementById('nameError').textContent = '';
    document.getElementById('emailError').textContent = '';
    document.getElementById('phoneError').textContent = '';
    document.getElementById('productError').textContent = '';

    const name = document.getElementById('buyer_name').value.trim();
    if (name === '') {
        document.getElementById('nameError').textContent = 'Please enter your name.';
        isValid = false;
    }

    const phone = document.getElementById('buyer_phone').value.trim();
    if (phone === '') {
        document.getElementById('phoneError').textContent = 'Please enter your phone number.';
        isValid = false;
    }

    const email = document.getElementById('buyer_email').value.trim();
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    if (email === '') {
        document.getElementById('emailError').textContent = 'Please enter your email to verify your account.';
        isValid = false;
    } else if (!emailPattern.test(email)) {
        document.getElementById('emailError').textContent = 'Please enter a valid email format.';
        isValid = false;
    }

    const checkboxes = document.querySelectorAll('input[name="products[]"]');
    const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
    
    if (!isChecked) {
        document.getElementById('productError').textContent = 'You must select at least one item to proceed to checkout.';
        isValid = false;
    }

    if (!isValid) {
        event.preventDefault();
    }
    return isValid;
};



// 6. PURCHASE PAGE: Live Purchase Calculator
document.addEventListener("DOMContentLoaded", function() {
    const purchaseForm = document.getElementById("purchaseForm");
    
    if (purchaseForm) {
        // Create the total display
        const liveTotalDisplay = document.createElement('h3');
        liveTotalDisplay.style.color = "rgb(30, 136, 229)";
        liveTotalDisplay.style.textAlign = "center";
        liveTotalDisplay.style.fontSize = "1.8em";
        liveTotalDisplay.style.margin = "20px 0";
        liveTotalDisplay.textContent = "Live Total: RM 0.00";

        // Insert the live total display above the checkout button
        const submitBtn = purchaseForm.querySelector(".submit-btn");
        purchaseForm.insertBefore(liveTotalDisplay, submitBtn);

        const checkboxes = document.querySelectorAll('input[name="products[]"]');

        // Calculate total and Update row highlights
        function updateLiveTotal() {
            let currentTotal = 0;

            checkboxes.forEach(function(box) {
                const row = box.closest('tr');
                if (box.checked) {
                    row.style.backgroundColor = "#e8f5e9";   // Light green highlight
                    const priceText = row.querySelectorAll('td')[3].textContent;
                    currentTotal += parseFloat(priceText.replace(/,/g, ''));
                } else {
                    row.style.backgroundColor = ""; 
                }
            });

            liveTotalDisplay.textContent = "Live Total: RM " + currentTotal.toFixed(2);
        }

        updateLiveTotal();

        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener("change", updateLiveTotal);
        });
    }
});