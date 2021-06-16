const table = document.getElementById('table');

if (table) {
    table.addEventListener('click', e => {
        if (e.target.className === 'btn btn-sm btn-danger btn-delete') {
            if (confirm('Are you sure?')) {
                const route = e.target.getAttribute('data-route');
                fetch(route, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}