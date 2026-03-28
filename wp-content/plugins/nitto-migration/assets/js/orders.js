function fetchPosOrders() {
    console.log("Fetch product function successfully called");

    if (!confirm("Are you sure you want to fetch products from the external database?")) {
        return;
    }

    const container = document.getElementById('nitto-products-list');
    container.innerHTML = '<p>Loading Products...</p>';

    fetch(nitto_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'nitto_fetch_pos_orders'
        })
    })
    .then(response => response.text())
    .then(data => {
        container.innerHTML = data;
    })
    .catch(error => {
        console.error(error);
        container.innerHTML = '<p>Error loading products.</p>';
    });
}

function fetchPromoOrders(){
    console.log("Fetch product function successfully called");

    if (!confirm("Are you sure you want to fetch products from the external database?")) {
        return;
    }

    const container = document.getElementById('nitto-products-list');
    container.innerHTML = "<p>Loading Promomaterial's Products...</p>";

    fetch(nitto_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'nitto_fetch_promo_orders'
        })
    })
    .then(response => response.text())
    .then(data => {
        container.innerHTML = data;
    })
    .catch(error => {
        console.error(error);
        container.innerHTML = '<p>Error loading products.</p>';
    });
}


let offset = 0;
const batchSize = 10;

function startPromoOrderMigration(){
    console.log("Start migrating in promomateerials");
    document.getElementById('migration-status').innerHTML = 'Starting migration...';
    runPromoBatch(); 
}
function startMigration() {
    document.getElementById('migration-status').innerHTML = 'Starting migration...';
    runBatch();
}

function runBatch() {

    const formData = new FormData();
    formData.append('action', 'nitto_migrate_pos_orders_batch');
    formData.append('offset', offset);
    formData.append('limit', batchSize);

    fetch(ajaxurl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(response => {

        if (response.success) {
            offset += batchSize;

            document.getElementById('migration-status').innerHTML =
                `Migrated: ${offset} orders`;

            if (response.data.has_more) {
                runBatch(); 
            } else {
                document.getElementById('migration-status').innerHTML +=
                    '<br>Migration completed ✅';
            }

        } else {
            document.getElementById('migration-status').innerHTML =
                'Error: ' + response.data;
        }

    })
    .catch(error => {
        document.getElementById('migration-status').innerHTML =
            'AJAX Error: ' + error;
    });
}

// For Promo
function runPromoBatch() {

    const formData = new FormData();
    formData.append('action', 'nitto_migrate_promo_orders_batch');
    formData.append('offset', offset);
    formData.append('limit', batchSize);

    fetch(ajaxurl, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(response => {

        // if (response.success) {
        //     offset += batchSize;

        //     document.getElementById('migration-status').innerHTML =
        //         `Migrated: ${offset} orders`;

        //     if (response.data.has_more) {
        //         runBatch(); 
        //     } else {
        //         document.getElementById('migration-status').innerHTML +=
        //             '<br>Migration completed ✅';
        //     }

        // } else {
        //     document.getElementById('migration-status').innerHTML =
        //         'Error: ' + response.data;
        // }
        const statusDiv = document.getElementById('migration-status');

        if (response.success) {
            offset += batchSize;
            statusDiv.classList.remove('error');
            statusDiv.innerHTML = `<strong>Status:</strong> Migrating ${offset} orders...`;

            if (response.data.has_more) {
                runPromoBatch(); 
            } else {
                statusDiv.classList.add('success');
                statusDiv.innerHTML = `Total Migrated: ${offset} orders<br><strong>Migration completed ✅</strong>`;
            }
        } else {
            statusDiv.classList.add('error');
            statusDiv.innerHTML = `<strong>Error:</strong> ${response.data}`;
        }

    })
    .catch(error => {
        document.getElementById('migration-status').innerHTML =
            'AJAX Error: ' + error;
    });
}