import axios from 'axios';
import sweetalert2 from 'sweetalert2';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Swal = sweetalert2;

window.Toast = sweetalert2.mixin({
    toast: true,
    position: "top-end",
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = sweetalert2.stopTimer;
        toast.onmouseleave = sweetalert2.resumeTimer;
    },
});