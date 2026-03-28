function fetchProduct() {
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
            action: 'nitto_fetch_products'
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

// document.addEventListener('click', function(e) {
//     if (e.target.classList.contains('migrate-btn')) {

//         const product = JSON.parse(e.target.dataset.product);

//         fetch(nitto_ajax.ajax_url, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             },
//             body: new URLSearchParams({
//                 action: 'nitto_migrate_product',
//                 product: JSON.stringify(product)
//             })
//         })
//         .then(res => res.json())
//         .then(res => {
//             alert(res.data);
//         });
//     }
// });

document.addEventListener('DOMContentLoaded', function () {

    const migrateBtn = document.getElementById('migrate-all-promo');

    if (!migrateBtn) {
        console.error('Button #migrate-all-promo not found');
        return;
    }

    migrateBtn.addEventListener('click', async function () {

        console.log("Migrate Promo products started");

        const buttons = document.querySelectorAll('.migrate-promo-btn');

        if (buttons.length === 0) {
            alert('No products found to migrate');
            return;
        }

        const products = [];

        buttons.forEach(btn => {
            try {
                products.push(JSON.parse(btn.dataset.product));
            } catch (e) {
                console.error('Invalid product JSON', e);
            }
        });

        const total = products.length;
        const batchSize = 10;

        let processed = 0;
        let failedProducts = [];

        while (processed < total) {

            const batch = products.slice(processed, processed + batchSize);

            console.log('📦 Sending batch:', batch);

            try {
                const res = await fetch(nitto_ajax.ajax_url, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({
                        action: 'nitto_migrate_promo_products_batch',
                        products: JSON.stringify(batch)
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    console.error('Batch error:', data);
                    alert('Batch failed: ' + data.data);
                    return;
                }

                failedProducts = failedProducts.concat(data.data.failed);

                processed += batch.length;

                const percent = Math.round((processed / total) * 100);

                const bar = document.getElementById('progress-bar');
                const text = document.getElementById('progress-text');

                if (bar) bar.style.width = percent + '%';
                if (text) text.innerText = percent + '%';

                console.log(`Processed ${processed}/${total}`);

            } catch (err) {
                console.error('Fetch failed:', err);
                alert('Network error');
                return;
            }
        }

        // Retry failed
        if (failedProducts.length > 0) {

            console.log('Retrying failed items...', failedProducts);

            await fetch(nitto_ajax.ajax_url, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'nitto_migrate_promo_products_batch',
                    products: JSON.stringify(failedProducts)
                })
            });
        }

        alert('Migration completed');
    });
});

async function migratePromoProduct() {

    console.log("Migrate POS products started");

    const buttons = document.querySelectorAll('.migrate-promo-btn');

    if (buttons.length === 0) {
        alert('No products found to migrate');
        return;
    }

    const products = [];

    buttons.forEach(btn => {
        try {
            products.push(JSON.parse(btn.dataset.product));
        } catch (e) {
            console.error('Invalid product JSON', e);
        }
    });

    const total = products.length;
    const batchSize = 10;

    let processed = 0;
    let failedProducts = [];

    while (processed < total) {

        const batch = products.slice(processed, processed + batchSize);

        console.log('📦 Sending batch:', batch);

        try {
            const res = await fetch(nitto_ajax.ajax_url, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'nitto_migrate_promo_products_batch',
                    products: JSON.stringify(batch)
                })
            });

            const data = await res.json();

            if (!data.success) {
                console.error('Batch error:', data);
                alert('Batch failed: ' + data.data);
                return;
            }

            failedProducts = failedProducts.concat(data.data.failed);

            processed += batch.length;

            const percent = Math.round((processed / total) * 100);

            const bar = document.getElementById('progress-bar');
            const text = document.getElementById('progress-text');

            if (bar) bar.style.width = percent + '%';
            if (text) text.innerText = percent + '%';

            console.log(`Processed ${processed}/${total}`);

        } catch (err) {
            console.error('Fetch failed:', err);
            alert('Network error');
            return;
        }
    }

    // Retry failed
    if (failedProducts.length > 0) {

        console.log('Retrying failed items...', failedProducts);

        await fetch(nitto_ajax.ajax_url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'nitto_migrate_promo_products_batch',
                products: JSON.stringify(failedProducts)
            })
        });
    }

    alert('🎉 Migration completed');
}





// For Point of purchase

function fetchPosProduct() {
    console.log("Fetch product function successfully called");

    if (!confirm("Are you sure you want to fetch products from the external database?")) {
        return;
    }

    const container = document.getElementById('nitto-products-list');
    container.innerHTML = '<p>Loading POS Products...</p>';

    fetch(nitto_ajax.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'nitto_fetch_pos_products'
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

// document.addEventListener('click', function(e) {
//     if (e.target.classList.contains('migrate-pos-btn')) {

//         const product = JSON.parse(e.target.dataset.product);

//         fetch(nitto_ajax.ajax_url, {
//             method: 'POST',
//             headers: {
//                 'Content-Type': 'application/x-www-form-urlencoded'
//             },
//             body: new URLSearchParams({
//                 action: 'nitto_migrate_pos_product',
//                 product: JSON.stringify(product)
//             })
//         })
//         .then(res => res.json())
//         .then(res => {
//             alert(res.data);
//         });
//     }
// });


document.addEventListener('DOMContentLoaded', function () {

    const migrateBtn = document.getElementById('migrate-all-pos');

    if (!migrateBtn) {
        console.error('Button #migrate-all-pos not found');
        return;
    }

    migrateBtn.addEventListener('click', async function () {

        console.log("Migrate POS products started");

        const buttons = document.querySelectorAll('.migrate-pos-btn');

        if (buttons.length === 0) {
            alert('No products found to migrate');
            return;
        }

        const products = [];

        buttons.forEach(btn => {
            try {
                products.push(JSON.parse(btn.dataset.product));
            } catch (e) {
                console.error('Invalid product JSON', e);
            }
        });

        const total = products.length;
        const batchSize = 10;

        let processed = 0;
        let failedProducts = [];

        while (processed < total) {

            const batch = products.slice(processed, processed + batchSize);

            console.log('📦 Sending batch:', batch);

            try {
                const res = await fetch(nitto_ajax.ajax_url, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams({
                        action: 'nitto_migrate_pos_products_batch',
                        products: JSON.stringify(batch)
                    })
                });

                const data = await res.json();

                if (!data.success) {
                    console.error('Batch error:', data);
                    alert('Batch failed: ' + data.data);
                    return;
                }

                failedProducts = failedProducts.concat(data.data.failed);

                processed += batch.length;

                const percent = Math.round((processed / total) * 100);

                const bar = document.getElementById('progress-bar');
                const text = document.getElementById('progress-text');

                if (bar) bar.style.width = percent + '%';
                if (text) text.innerText = percent + '%';

                console.log(`Processed ${processed}/${total}`);

            } catch (err) {
                console.error('Fetch failed:', err);
                alert('Network error');
                return;
            }
        }

        // Retry failed
        if (failedProducts.length > 0) {

            console.log('Retrying failed items...', failedProducts);

            await fetch(nitto_ajax.ajax_url, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'nitto_migrate_pos_products_batch',
                    products: JSON.stringify(failedProducts)
                })
            });
        }

        alert('Migration completed');
    });
});

async function migratePosProduct() {

    console.log("Migrate POS products started");

    const buttons = document.querySelectorAll('.migrate-pos-btn');

    if (buttons.length === 0) {
        alert('No products found to migrate');
        return;
    }

    const products = [];

    buttons.forEach(btn => {
        try {
            products.push(JSON.parse(btn.dataset.product));
        } catch (e) {
            console.error('Invalid product JSON', e);
        }
    });

    const total = products.length;
    const batchSize = 10;

    let processed = 0;
    let failedProducts = [];

    while (processed < total) {

        const batch = products.slice(processed, processed + batchSize);

        console.log('📦 Sending batch:', batch);

        try {
            const res = await fetch(nitto_ajax.ajax_url, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams({
                    action: 'nitto_migrate_pos_products_batch',
                    products: JSON.stringify(batch)
                })
            });

            const data = await res.json();

            if (!data.success) {
                console.error('Batch error:', data);
                alert('Batch failed: ' + data.data);
                return;
            }

            failedProducts = failedProducts.concat(data.data.failed);

            processed += batch.length;

            const percent = Math.round((processed / total) * 100);

            const bar = document.getElementById('progress-bar');
            const text = document.getElementById('progress-text');

            if (bar) bar.style.width = percent + '%';
            if (text) text.innerText = percent + '%';

            console.log(`Processed ${processed}/${total}`);

        } catch (err) {
            console.error('Fetch failed:', err);
            alert('Network error');
            return;
        }
    }

    // Retry failed
    if (failedProducts.length > 0) {

        console.log('Retrying failed items...', failedProducts);

        await fetch(nitto_ajax.ajax_url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({
                action: 'nitto_migrate_pos_products_batch',
                products: JSON.stringify(failedProducts)
            })
        });
    }

    alert('🎉 Migration completed');
}
