$('.product-button__add').on('click', function (event) {
    event.preventDefault();
    let name = $(this).data('name');
    $.ajax({
        url: '/cart/add',
        data: {
            name: name
        },
        type: 'GET',
        success: function (res) {
            console.log(res);
            $('#cart .modal-content').html(res);
            $('.menu-quantity').html('(' + $('.total-quantity').html() + ')');
        }, error: function () {
            alert('error');
        }
    })
});

function openCart(event) {
    event.preventDefault();
    $.ajax({
        url: '/cart/open',
        type: 'GET',
        success: function (data) {
            $('#cart .modal-content').html(data);
            $('#cart').modal('show');
        }, error: function () {
            alert('error');
        }
    })
}

$('.modal-content').on('click', '.btn-close', function () {
    $('#cart').modal('hide');
});

function clearCart(event) {
    if (confirm('Вы точно хотите очистеть корзину? :(')) {
        event.preventDefault();
        $.ajax({
            url: '/cart/clear', type: 'GET', success: function (data) {
                $('#cart .modal-content').html(data);
                $('.menu-quantity').html('(0)');
            }, error: function () {
                alert('error');
            }
        })
    }
}

$('.modal-content').on('click', '.delete', function () {
    let id = $(this).data('id');
    $.ajax({
        url: '/cart/delete', data: {id: id}, type: 'GET', success: function (data) {
            $('#cart .modal-content').html(data);
            if ($('.total-quantity').html()) {
                $('.menu-quantity').html('(' + $('.total-quantity').html() + ')');
            } else {
                $('.menu-quantity').html('(0)');
            }
        }, error: function () {
            alert('error');
        }
    })
});
$('.modal-content').on('click', '.btn-next', function () {
    $.ajax({
        url: '/cart/order',
        type: 'GET',
        success: function (data) {
            $('#cart').modal('hide');
            $('#order').modal('show');
            $('#order .modal-content').html(data);
        }, error: function () {
            alert('error');
        }
    })
});

let split = window.location.href.split('/');
let id = split[split.length - 1];
let nav = document.getElementsByClassName('nav-link');
for (let i = 0; i < nav.length; i++) {
    if (nav[i].getAttribute('data-id') == id) {
        nav[i].classList.add('active');
        break;
    } else if (!id) {
        nav[0].classList.add('active');
        break;
    }
}

