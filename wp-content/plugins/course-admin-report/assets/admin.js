document.querySelectorAll('.btn-primary-new').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.btn-primary-new').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});

function openTab(id){
    console.log('Opening tab:', id);
    if(id === 'details-ladger-view') {  
        document.getElementById('details-ladger-view').style.display = 'block'; 
        document.getElementById('record-statement').style.display = 'none';
    }else{
        document.getElementById('details-ladger-view').style.display = 'none';      
        document.getElementById('record-statement').style.display = 'block';
    }
}