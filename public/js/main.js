const products = document.getElementById('products');
if (products) {
    products.addEventListener('click', e => {
        if (e.target.getAttribute("data-action") === 'delete-product') {
            const productName = e.target.getAttribute('data-name');
            if (confirm('Are you sure to delete the product \'' + productName + '\'?')) {
                const id = e.target.getAttribute('data-id');
                fetch(`/product/delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    })
}

$(document).ready(function() {
    $("#productSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $(".filterProduct tr").filter(function() {
            $(this).toggle($(this).data("tag").toLowerCase().indexOf(value) > -1)
        });
    });
});
