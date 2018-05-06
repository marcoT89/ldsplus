import swal from 'sweetalert';

export default function (params = {}) {
    return swal({
        title: params.title || 'Tem certeza?',
        text: params.text || 'Esta operação não poderá ser desfeita.',
        icon: params.icon || 'warning',
        buttons: {
            cancel: {
                text: params.cancel || 'Não',
                value: false,
                visible: true,
                closeModal: true,
            },
            confirm: {
                text: params.confirm || 'Sim',
                value: true,
                closeModal: false,
            },
        },
        dangerMode: params.dangerMode || false,
    })
    .then(confirmed => confirmed ? Promise.resolve(confirmed) : Promise.reject(false));
}
