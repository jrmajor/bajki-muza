import prettyBytes from 'pretty-bytes'
import Croppr from 'croppr'

export default function (data) {
  return {
    picker: 'current',

    source: data.photo.source,

    crop: {
      x: 0,
      y: 0,
      width: 0,
      height: 0
    },

    pickers: {
      current: {
        uri: data.photo.uri,
        source: data.photo.source,
        crop: data.photo.crop,
      },

      upload: {
        uri: '',
        file: ''
      },

      uri: {
        uri: '',
        source: ''
      }
    },

    init () {
      if (this.pickers.current.uri !== null) this.updateCroppr()
    },

    removePhoto () {
      this.picker = 'remove'
      this.source = ''
    },

    resetPickerToCurrent () {
      this.picker = 'current'
      this.source = this.pickers.current.source

      this.updateCroppr()
    },

    fileSelected (files, value) {
      if (value === '') return this.resetPickerToCurrent()

      this.pickers.uri.uri = ''

      this.picker = 'upload'
      this.pickers.upload.uri = URL.createObjectURL(files[0])

      this.updateCroppr()
    },

    setPhotoUri (uri, source) {
      if (this.picker === 'uri' && this.pickers.uri.uri === uri) {
        return this.resetPickerToCurrent()
      }

      this.pickers.upload.file = ''

      this.picker = 'uri'
      this.pickers.uri.uri = uri
      this.pickers.uri.source = this.source = source

      this.updateCroppr()
    },

    labelText (files) {
      if (this.picker === 'upload') return files[0].name

      if (this.picker === 'uri') {
        if (this.pickers.uri.source === 'discogs') return 'Wybrano z Discogsa'
        if (this.pickers.uri.source === 'filmpolski') return 'Wybrano z FilmuPolskiego'
      }

      return 'Wybierz plik'
    },

    size (files) {
      if (this.picker !== 'upload') return
      if (this.pickers.upload.file === '') return

      return prettyBytes(files[0].size)
    },

    initCroppr (src, startSize, onInitialize) {
      if (typeof window.artistPhotoCroppr !== 'undefined') {
        window.artistPhotoCroppr.destroy()
      }

      const el = document.getElementById('artist-photo-croppr')

      el.setAttribute('src', src)

      const onCropEnd = (crop) => this.crop = crop

      window.artistPhotoCroppr = new Croppr(el, {
        minSize: [100, 100, 'px'],
        startSize,
        onInitialize,
        onCropEnd
      })
    },

    updateCroppr () {
      const uri = this.pickers[this.picker].uri

      if (uri === null) return

      let onInitialize;

      if (this.picker === 'current') {
        onInitialize = instance => {
          const el = instance.imageEl

          const actualWidth = el.naturalWidth;
          const actualHeight = el.naturalHeight;

          const { width: elementWidth, height: elementHeight } = el.getBoundingClientRect();

          const factorX = actualWidth / elementWidth;
          const factorY = actualHeight / elementHeight;

          const actualCrop = {
            x: Math.round(this.pickers.current.crop.x / factorX),
            y: Math.round(this.pickers.current.crop.y / factorY),
            width: Math.round(this.pickers.current.crop.width / factorX),
            height: Math.round(this.pickers.current.crop.height / factorY)
          }

          instance
            .resizeTo(actualCrop.width, actualCrop.height)
            .moveTo(actualCrop.x, actualCrop.y)

          this.updateCrop()
        }
      } else onInitialize = instance => instance

      this.initCroppr(uri, [100, 100, '%'], onInitialize)
    },

    updateCrop () {
      this.crop = window.artistPhotoCroppr.getValue()
    }
  }
}
