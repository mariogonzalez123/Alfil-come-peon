
function comprobarPosicion(e) {
    $('#mensaje').empty();
    var celda = e.target;
    if (celda.nodeName === 'TD' && (celda.innerHTML === "" || celda.innerHTML === "+")) {
        $.ajax({
            url: 'index.php',
            dataType: 'JSON',
            type: 'POST',
            data: {
                f: celda.dataset.x,
                c: celda.dataset.y
            },
            success: function (result) {
                if (result.X !== undefined) {
                    $(`#${result.XUsuario}${result.YUsuario}`).empty();
                    $(`#${result.X}${result.Y}`).html("*");
                }
                if (result.XCpu !== undefined) {
                    $(`#${result.XCpu}${result.YCpu}`).empty();
                    $(`#${result.XMovCPU}${result.YMovCPU}`).html("+");
                }
                if (result.gameRes !== undefined) {
                    switch (result.gameRes) {
                        case 1:
                            $('#mensaje').html("¡HAS GANADO!");
                            break;
                        case - 1:
                            $('#mensaje').html("¡HAS PERDIDO!");
                            break;
                    }
                    $('table').unbind('click');
                }
                if (result.error !== undefined) {
                    $('#mensaje').html(result.error);
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert(XMLHttpRequest.status + ' ' + textStatus);
            }
        });
    }
}
$(document).ready(function () {
    $('table').click(comprobarPosicion);
});
