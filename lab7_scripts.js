
//==== VALIDATE INPUT FUNCTION ====//
function validate_input(){
    const name = document.getElementById("sname").value;
    const age = document.getElementById("age").value;
    const email = document.getElementById("email").value;
    const course = document.getElementById("scourse").value;
    const course = document.getElementById("yearLevel").value;

    if (!name || !age || !email || !course) {
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

//==== SEARCH STUDENT FUNCTION ====//
function find_student(){
    const searchID = document.getElementById("searchID").value;

    const student = added_students.find(s => s.id === searchID); // search for student by ID

    if (!student) {
        document.getElementById("result").innerHTML = "Student record does not exist.";
        return;
    }

    document.getElementById("result").innerHTML = `
    <strong>ID:</strong> 2024-${student.id}<br>
    <strong>Name:</strong> ${student.name}<br>
    <strong>Age:</strong> ${student.age}<br>
    <strong>Email:</strong> ${student.email}<br>
    <strong>Course:</strong> ${student.course}
    `;
}

//==== CONNECT FORM SUBMIT EVENT (Enter + Button) ====//
const form = document.getElementById("studentForm");
form.addEventListener("submit", add_student);