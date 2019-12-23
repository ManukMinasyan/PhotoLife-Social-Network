export default function (user_id) {
    let auth_id = localStorage.getItem('auth_user_id');
    return parseInt(auth_id) === parseInt(user_id);
    // switch (action) {
    //     case 'follow':
    //         return parseInt(auth_id) !== parseInt(subject);
    //         break;
    //     default:
    //         return false;
    // }
}