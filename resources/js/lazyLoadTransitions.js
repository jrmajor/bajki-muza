window.transitionLazyLoadedImages = () => {
  document.body
    .querySelectorAll('img[loading=lazy], img[loading=eager]')
    .forEach((image) => {
      image.complete && image.classList.remove('opacity-0')

      image.onload = (event) => event.target.classList.remove('opacity-0')
    })
}

window.transitionLazyLoadedImages()

document.addEventListener('livewire:load', () => {
  window.livewire.hook('message.processed', () => {
    window.transitionLazyLoadedImages()
  })
})
