//==== VALIDATE INPUT FUNCTION ====//
function validate_input(){
    const name = document.getElementById("sname").value;
    const age = document.getElementById("sage").value;      
    const email = document.getElementById("semail").value;
    const course = document.getElementById("scourse").value;
    const year_level = document.getElementById("yearLevel").value;
    const fileInput = document.getElementById("file-upload");
    const message = document.getElementById("message");

    if (!name || !age || !email || !course || !fileInput.files.length) {
        document.getElementById('message').textContent = 'Please fill in all fields.';
        return false;
    }

    const name_parts = name.trim().split(/\s+/);

    const valid_name = name_parts.length >= 2 && // should have at least 2 parts (first and last name)
                  name_parts.every(part => /^[A-Za-z]+$/.test(part)); // each part should contain only letters

    if (!valid_name || name.replace(/\s/g, "").length < 5) {
        document.getElementById('message').textContent =
            'Enter a valid full name.';
        return false;
    }

    if (age < 18 || age > 99) {
        document.getElementById('message').textContent = 'Please enter a valid age.';
        return false;
    }

    const student_email = /^[^\s@]+@up\.edu\.ph$/;
    if (!student_email.test(email)) {
        document.getElementById('message').textContent = 'Please enter a valid email address.';
        return false;
    }

    return true;
}

document.addEventListener("DOMContentLoaded", function() { // to show image preview
    const fileInput = document.getElementById('file-upload');
    const fileLabel = document.getElementById('choose');
    const preview = document.getElementById('preview');
    const icon = document.getElementById('placeholder-icon');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                fileLabel.textContent = file.name;
                fileLabel.style.color = "#2e7d32"; 
                fileLabel.style.fontWeight = "bold";

                const reader = new FileReader();
                reader.onload = function(e) {
                    if (preview) {
                        preview.src = e.target.result;
                        preview.style.display = 'block'; 
                        if (icon) icon.style.display = 'none';
                    }
                }
                reader.readAsDataURL(file);
                
            } else {
                fileLabel.textContent = "Choose a file";
                fileLabel.style.color = ""; 
                if (preview) preview.style.display = 'none';
                if (icon) icon.style.display = 'block';
            }
        });
    }
});

// for maintaining scroll position every reload
document.addEventListener("click", function(e) {
    // Check if the clicked element is a submit button or inside a form
    if (e.target.type === "submit" || e.target.tagName === "BUTTON") {
        localStorage.setItem("scrollPosition", window.scrollY);
    }
});

window.addEventListener("load", function() {
    const scrollPos = localStorage.getItem("scrollPosition");
    if (scrollPos) {
        window.scrollTo(0, parseInt(scrollPos));
        localStorage.removeItem("scrollPosition");
    }
});

// to handle new image update
function previewUpdateImage(input) {
    const preview = document.getElementById('preview-update');
    const label = document.getElementById('label-update');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            label.innerText = input.files[0].name;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
