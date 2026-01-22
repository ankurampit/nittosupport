function addNewAd(id){
    console.log("Add New Ad button clicked");
    if(id === 'add_new'){
        document.getElementById("new-ad-form").style.display = "block";
        document.getElementById('print-ads-table').style.display = "none";
        document.getElementById('print-ads-btn').style.display = "none";
        document.getElementById('back-btn').style.display = "inline-block";
        document.getElementById("acf-edit-form").style.display = "none";
    }
    else{
        document.getElementById("new-ad-form").style.display = "none";
        document.getElementById('print-ads-table').style.display = "block";
        document.getElementById('print-ads-btn').style.display = "inline-block";
        document.getElementById('back-btn').style.display = "none";
        document.getElementById("acf-edit-form").style.display = "none";

    }
}

document.addEventListener('DOMContentLoaded', () => {

    const tbody = document.getElementById('materials-sortable');
    let draggedRow = null;

    tbody.addEventListener('dragstart', (e) => {
        if (e.target.tagName === 'TR') {
            draggedRow = e.target;
            e.target.classList.add('dragging');
        }
    });

    tbody.addEventListener('dragend', (e) => {
        if (e.target.tagName === 'TR') {
            e.target.classList.remove('dragging');
        }
    });

    tbody.addEventListener('dragover', (e) => {
        e.preventDefault();
        const targetRow = e.target.closest('tr');
        if (!targetRow || targetRow === draggedRow) return;

        const rect = targetRow.getBoundingClientRect();
        const next = (e.clientY - rect.top) > (rect.height / 2);
        tbody.insertBefore(draggedRow, next ? targetRow.nextSibling : targetRow);
    });

    tbody.addEventListener('drop', () => {
        saveOrder();
    });

    function saveOrder() {
        const order = [];
        tbody.querySelectorAll('tr').forEach((row, index) => {
            order.push({
                id: row.dataset.postId,
                position: index
            });
        });

        // fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
        //     method: 'POST',
        //     headers: {
        //         'Content-Type': 'application/x-www-form-urlencoded'
        //     },
        //     body: new URLSearchParams({
        //         action: 'save_materials_order',
        //         order: JSON.stringify(order)
        //     })
        // });
    }
});


document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('show_password');
    const passwordInput = document.getElementById('Password');
    const icon = toggleBtn.querySelector('i');

    toggleBtn.addEventListener('click', function () {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});


