Livewire.onLoad(() => {
    window.addEventListener('scroll', () => {
        let swipeDownRefresh = document.getElementById('swipe-down-refresh');
        let infiniteScroll = document.getElementById('infinite-scroll');

        if (swipeDownRefresh && swipeDownRefresh.classList.contains('hidden') && window.scrollY < -100) {
            document.getElementById('swipe-down-refresh').classList.remove('hidden');
            setTimeout(() => location.reload(), 100);
        }

        if (infiniteScroll && infiniteScroll.classList.contains('hidden') && (window.innerHeight + window.scrollY) >= document.body.offsetHeight - 100) {
            document.getElementById('infinite-scroll').classList.remove('hidden');
            Livewire.emit('infiniteScroll');
        }
    });

    Livewire.on('bodyScrollLock', () => {
        document.body.style.width = document.body.scrollWidth + 'px';
        document.body.style.overflow = 'hidden';
    });

    Livewire.on('bodyScrollUnlock', () => {
        document.body.style.width = 'auto';
        document.body.style.overflow = 'auto';
    });
});
