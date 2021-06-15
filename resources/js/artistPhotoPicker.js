import prettyBytes from 'pretty-bytes'
import Cropper from './cropper'

export default function (data) {
  return {
    picker: 'current',

    source: data.photo.source,

    grayscale: data.photo.grayscale,

    crop: {
      face: { x: 0, y: 0, width: 0, height: 0 },
      image: { x: 0, y: 0, width: 0, height: 0 },
    },

    pickers: {
      current: {
        uri: data.photo.uri,
        source: data.photo.source,
        crop: data.photo.crop,
        grayscale: data.photo.grayscale,
      },

      upload: { uri: '', file: '' },

      uri: { uri: '', source: '' },
    },

    init() {
      if (this.pickers.current.uri !== null) this.updateCropper()
    },

    removePhoto() {
      this.picker = 'remove'
      this.source = ''
    },

    resetPickerToCurrent() {
      this.picker = 'current'
      this.source = this.pickers.current.source
      this.grayscale = this.pickers.current.grayscale

      this.updateCropper()
    },

    fileSelected(files, value) {
      if (value === '') return this.resetPickerToCurrent()

      this.pickers.uri.uri = ''

      this.picker = 'upload'
      this.pickers.upload.uri = URL.createObjectURL(files[0])

      this.grayscale = true

      this.updateCropper()
    },

    setPhotoUri(uri, source) {
      if (this.picker === 'uri' && this.pickers.uri.uri === uri) {
        return this.resetPickerToCurrent()
      }

      this.pickers.upload.file = ''

      this.picker = 'uri'
      this.pickers.uri.uri = uri
      this.pickers.uri.source = this.source = source
      this.grayscale = true

      this.updateCropper()
    },

    labelText(files) {
      if (this.picker === 'upload') return files[0].name

      if (this.picker === 'uri') {
        if (this.pickers.uri.source === 'discogs') return 'Wybrano z Discogsa'
        if (this.pickers.uri.source === 'filmpolski') return 'Wybrano z FilmuPolskiego'
      }

      return 'Wybierz plik'
    },

    size(files) {
      if (this.picker !== 'upload') return
      if (this.pickers.upload.file === '') return

      return prettyBytes(files[0].size)
    },

    initCropper(src, onFaceInitialize, onInitialize) {
      if (typeof this.artistFacePhotoCropper !== 'undefined') {
        this.artistFacePhotoCropper.destroy()
      }

      if (typeof this.artistPhotoCropper !== 'undefined') {
        this.artistPhotoCropper.destroy()
      }

      const faceCropperEl = document.getElementById('artist-face-photo-cropper')
      const imageCropperEl = document.getElementById('artist-photo-cropper')

      faceCropperEl.setAttribute('src', src)
      imageCropperEl.setAttribute('src', src)

      this.artistFacePhotoCropper = new Cropper(faceCropperEl, {
        aspectRatio: 1,
        minSize: [50, 50, 'px'],
        startSize: [100, 100, '%'],
        onInitialize: onFaceInitialize,
        onCropEnd: (crop) => (this.crop.face = crop),
      })

      this.artistPhotoCropper = new Cropper(imageCropperEl, {
        minSize: [50, 50, 'px'],
        startSize: [100, 100, '%'],
        onInitialize,
        onCropEnd: (crop) => (this.crop.image = crop),
      })
    },

    updateCropper() {
      const uri = this.pickers[this.picker].uri

      if (uri === null) return

      const crop = this.pickers.current.crop

      const onFaceInitialize = this.picker === 'current'
        ? (instance) => instance
          .resizeToScaled(crop.face.size, crop.face.size)
          .moveToScaled(crop.face.x, crop.face.y)
        : (instance) => instance

      const onInitialize = this.picker === 'current'
        ? (instance) => instance
          .resizeToScaled(crop.image.width, crop.image.height)
          .moveToScaled(crop.image.x, crop.image.y)
        : (instance) => instance

      this.initCropper(uri, onFaceInitialize, onInitialize)
    },

    updateCrop() {
      this.crop.face = this.artistFacePhotoCropper.getValue()
      this.crop.image = this.artistPhotoCropper.getValue()
    },
  }
}
