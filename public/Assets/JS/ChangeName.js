function events() {
    const back = document.querySelector('.back');
    back.onclick = () => {
        window.location.href = '/user/profile';
    };
}

events();