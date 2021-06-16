const table = document.getElementById('table');

if (table) {
    table.addEventListener('click', e => {
        if (e.target.className === 'btn btn-sm btn-danger btn-delete') {
            if (confirm('Are you sure?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/categories/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}