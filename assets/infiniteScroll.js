window.addEventListener('scroll', () => {
    const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
    console.log(scrollTop, scrollHeight, clientHeight);
    console.log("hi");
})