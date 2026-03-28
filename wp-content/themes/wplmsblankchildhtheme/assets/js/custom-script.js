$('#show_password').hover(function functionName() {
        //Change the attribute to text
        $('#password').attr('type', 'text');
        $('#show_password .fa').removeClass('fa-eye').addClass('fa-eye-slash');
    }, function () {
        //Change the attribute back to password
        $('#password').attr('type', 'password');
        $('#show_password .fa').removeClass('fa-eye-slash').addClass('fa-eye');
    }
);
   
// Number of entries to show
window.addEventListener("load", () => {

    const table = document.querySelector("#example");
    const lengthSelect = document.querySelector("#table-length");
    const info = document.querySelector("#example_info");

    if (!table || !lengthSelect || !info) return;

    const rows = table.querySelectorAll("tbody tr");
    const totalRows = rows.length;

    const updateTable = () => {

        const length = Number(lengthSelect.value);

        rows.forEach((row, index) => {
            row.style.display = index < length ? "" : "none";
        });

        const showingTo = Math.min(length, totalRows);

        info.textContent = `Showing 1 to ${showingTo} of ${totalRows} entries`;
    };

    lengthSelect.addEventListener("change", updateTable);

    updateTable();

});


// Export table to Excel    
// document.getElementById("exportTable").addEventListener("click", function (e) {

//     e.preventDefault();

//     const table = document.getElementById("example");
//     if (!table) return;

//     const clonedTable = table.cloneNode(true);

//     const rows = clonedTable.querySelectorAll("tr");

//     rows.forEach(row => {

//         // remove role dropdown column
//         const roleColumn = row.querySelector(".role-action-column");
//         if (roleColumn) roleColumn.remove();

//         // remove action buttons column
//         const actionColumn = row.querySelector(".management-action-button");
//         if (actionColumn) actionColumn.remove();

//     });

//     const workbook = XLSX.utils.table_to_book(clonedTable, { sheet: "Users" });

//     XLSX.writeFile(workbook, "users.xlsx");

// });

function exportUser(data) {

    const formattedData = data.map(user => ({
        ID: user.ID,
        Username: user.Username,
        Email: user.Email,
        First_Name: user["First Name"],
        Last_Name: user["Last Name"],
        Phone: user.phone,
        Company_Name: user.Companyname,
        Dealer_Number: user.Dealernumber,
        Address: user.Address,
        City: user.City,
        State: user.State,
        Zip_Code: user.Zipcode,
        Role: user.Role,
        User_Group: user.usergroup,
        Wallet_Balance: user.wallet_balance,
        Dealer_Access: user.dealer_access,
        E_Sales_Promo: user.esalespromo,
        E_Surveys: user.esurveys,
        E_Tire_Info: user.etireinfo,
        Language: user.language_preference
    }));

    const worksheet = XLSX.utils.json_to_sheet(formattedData);

    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Users");

    XLSX.writeFile(workbook, "users.xlsx");
}

window.addEventListener("load", () => {

    const table = document.querySelector("#example");
    const searchInput = document.querySelector("#example_filter input");

    if (!table || !searchInput) return;

    const rows = table.querySelectorAll("tbody tr");

    searchInput.addEventListener("input", function () {

        const searchValue = this.value.toLowerCase();

        rows.forEach(row => {

            const text = row.textContent.toLowerCase();

            if (text.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }

        });

    });

});

let userIdToDelete = null;
function openDeleteModal(userId) {
    userIdToDelete = userId;
    document.getElementById('delete-modal').classList.add('active');
}

function closeDeleteModal() {
    document.getElementById('delete-modal').classList.remove('active');
    userIdToDelete = null;
}

// function confirmDeleteUser() {
//     console.log("DeleteUser");
//     console.log('user id', userIdToDelete)
//     if (!userIdToDelete) return;

//     fetch(my_ajax.ajax_url, {
//         method: "POST",
//         headers: {
//             "Content-Type": "application/x-www-form-urlencoded",
//         },
//         body: new URLSearchParams({
//             action: "delete_user_multisite",
//             user_id: userIdToDelete,
//             nonce: my_ajax.nonce
//         })
//     })
//     .then(res => res.json())
//     .then(data => {
//         if (data.success) {
//             const row = document.getElementById(`user-row-${userIdToDelete}`);

//             if (row) {
//                 row.remove(); // instantly removes row
//             }

//             closeDeleteModal();
//         } else {
//             console.error("Error:", data.data);
//         }
//     })
//     .catch(() => {
//         alert("Something went wrong");
//     });
// }

function confirmDeleteUser(btn) {
    console.log("DeleteUser");
    console.log('user id', userIdToDelete);

    if (!userIdToDelete) return;

    const loader = document.getElementById('delete-loader');

    // ✅ Show loader
    if (loader) loader.style.display = 'block';

    // ✅ Disable button
    if (btn) {
        btn.disabled = true;
        btn.innerText = "Deleting...";
    }

    fetch(my_ajax.ajax_url, {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: new URLSearchParams({
            action: "delete_user_multisite",
            user_id: userIdToDelete,
            nonce: my_ajax.nonce
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {

            const row = document.getElementById(`user-row-${userIdToDelete}`);

            if (row) {
                // ✅ Smooth animation
                row.style.transition = "all 0.4s ease";
                row.style.opacity = "0";
                row.style.transform = "translateX(-20px)";

                setTimeout(() => {

                    // ✅ Check if DataTable exists
                    if (typeof jQuery !== 'undefined' && jQuery.fn.DataTable && jQuery.fn.DataTable.isDataTable('#example')) {
                        const table = jQuery('#example').DataTable();
                        table.row(row).remove().draw(false);
                    } else {
                        // ✅ fallback (no DataTables)
                        row.remove();
                    }

                }, 400);
            }

            closeDeleteModal();
        } else {
            console.error("Error:", data.data);
        }
    })
    .catch(err => {
        console.error("Request failed:", err);
    })
    .finally(() => {
        // ✅ Reset UI
        if (loader) loader.style.display = 'none';

        if (btn) {
            btn.disabled = false;
            btn.innerText = "Delete";
        }
    });
}


// Change Password

document.addEventListener('DOMContentLoaded', function () {
    console.log("DOM ready");
    const currentPass = document.getElementById('current_pass');
    const newPass = document.getElementById('new_pass');
    const confirmPass = document.getElementById('confirm_pass');
    const msg = document.getElementById('pass-check-msg');

    let timer;
    const delay = 100;

    currentPass.addEventListener('input', function () {

        console.log("Check Current Password");
        clearTimeout(timer);

        const value = this.value;

        if (value.length < 4) {
            disableFields();
            msg.innerHTML = '';
            return;
        }

        timer = setTimeout(() => {

            msg.innerHTML = '<span style="color:orange;">Checking your current password...</span>';
            fetch(ajax_obj.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'check_current_password',
                    current_pass: value,
                    nonce: ajax_obj.nonce
                })
            })
            .then(res => res.json())
            .then(data => {

                console.log(data); // debug

                if (data.success) {
                    msg.innerHTML = '<span style="color:green;">✔ Correct password</span>';
                    enableFields();
                } else {
                    msg.innerHTML = '<span style="color:red;">✖ Incorrect password</span>';
                    disableFields();
                }

            })
            .catch(() => {
                msg.innerHTML = '<span style="color:red;">Server error</span>';
                disableFields();
            });

        }, delay);
    });

    function enableFields() {
        if (newPass) newPass.disabled = false;
        if (confirmPass) confirmPass.disabled = false;
    }

    function disableFields() {
        if (newPass) newPass.disabled = true;
        if (confirmPass) confirmPass.disabled = true;
    }

});



document.addEventListener('DOMContentLoaded', function () {

    const newPass = document.getElementById('new_pass');
    const strengthBar = document.getElementById('strength_bar');
    const strengthText = document.getElementById('strength_text');

    if (!newPass || !strengthBar) {
        console.error("Strength elements not found");
        return;
    }

    newPass.addEventListener('input', function () {

        const val = this.value;
        let strength = 0;

        // Reset
        strengthBar.style.width = "0%";
        strengthBar.style.background = "";
        strengthText.innerHTML = "";

        if (val.length === 0) return;

        // Rules
        if (val.length >= 6) strength++;
        if (/[A-Z]/.test(val)) strength++;
        if (/[0-9]/.test(val)) strength++;
        if (/[^A-Za-z0-9]/.test(val)) strength++;

        // Apply UI
        if (strength <= 1) {
            strengthBar.style.width = "25%";
            strengthBar.style.background = "red";
            strengthText.innerHTML = "Weak password";
            strengthText.style.color = "red";
        }
        else if (strength === 2 || strength === 3) {
            strengthBar.style.width = "65%";
            strengthBar.style.background = "orange";
            strengthText.innerHTML = "Medium strength";
            strengthText.style.color = "orange";
        }
        else {
            strengthBar.style.width = "100%";
            strengthBar.style.background = "green";
            strengthText.innerHTML = "Strong password";
            strengthText.style.color = "green";
        }

    });

});

document.addEventListener('DOMContentLoaded', function () {

    const newPass = document.getElementById('new_pass');
    const confirmPass = document.getElementById('confirm_pass');
    const confirmMsg = document.getElementById('confirm_msg');

    if (!newPass || !confirmPass || !confirmMsg) {
        console.error("Confirm password elements not found");
        return;
    }

    function checkMatch() {
        const newVal = newPass.value;
        const confirmVal = confirmPass.value;

        if (confirmVal.length === 0) {
            confirmMsg.innerHTML = "";
            confirmPass.style.borderColor = "";
            return;
        }

        if (newVal === confirmVal) {
            confirmMsg.innerHTML = "✔ Passwords match";
            confirmMsg.style.color = "green";
            confirmPass.style.borderColor = "green";
        } else {
            confirmMsg.innerHTML = "✖ Passwords do not match";
            confirmMsg.style.color = "red";
            confirmPass.style.borderColor = "red";
        }
    }

    // Trigger on typing
    confirmPass.addEventListener('input', checkMatch);
    newPass.addEventListener('input', checkMatch);

});

const form = document.getElementById('password_form');

if (form) {

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const current_pass = document.getElementById('current_pass').value;
        const new_pass = document.getElementById('new_pass').value;
        const confirm_pass = document.getElementById('confirm_pass').value;

        const msg = document.getElementById('pass-check-msg');
        
        if (new_pass !== confirm_pass) {
            msg.innerHTML = '<span style="color:red;">Passwords do not match</span>';
            return;
        }

        msg.innerHTML = '<span style="color:orange;">Updating password...</span>';

        fetch(ajax_obj.ajax_url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: 'update_user_password',
                current_pass: current_pass,
                new_pass: new_pass,
                nonce: ajax_obj.nonce
            })
        })
        .then(res => res.json())
        .then(data => {

            if (data.success) {
                msg.innerHTML = '<span style="color:green;">✔ Password updated successfully</span>';

                form.reset();
                
                document.getElementById('new_pass').disabled = true;
                document.getElementById('confirm_pass').disabled = true;

            } else {
                msg.innerHTML = `<span style="color:red;">${data.data.message}</span>`;
            }
        })
        .catch(() => {
            msg.innerHTML = '<span style="color:red;">Server error</span>';
        });

    });

}