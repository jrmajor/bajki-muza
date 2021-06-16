import prettyBytes from 'pretty-bytes'
import Cropper from './cropper'

export default function (data, artistForm) {
  return {
    artistForm,

    activePicker: 'current',

    uri: null,
    source: null,
    crop: {
      face: { x: 0, y: 0, width: 0, height: 0 },
      image: { x: 0, y: 0, width: 0, height: 0 },
    },
    grayscale: null,

    uriFrom: null,
    file: null,

    original: {
      uri: data.uri,
      source: data.source,
      crop: data.crop,
      grayscale: data.grayscale,
    },

    croppers: { face: null, image: null },

    init() {
      if (this.original.uri !== null) {
        this.resetPickerToCurrent()
      }

      return this
    },

    setPicker(picker) {
      this.uri = this.uriFrom = this.file = null
      this.crop = {
        face: { x: 0, y: 0, width: 0, height: 0 },
        image: { x: 0, y: 0, width: 0, height: 0 },
      }
      this.grayscale = true

      this.activePicker = picker
    },

    resetPickerToCurrent() {
      this.setPicker('current')

      this.uri = this.original.uri
      this.source = this.original.source
      this.grayscale = this.original.grayscale

      this.crop = {
        face: {
          x: this.original.crop.face.x,
          y: this.original.crop.face.y,
          width: this.original.crop.face.size,
          height: this.original.crop.face.size,
        },
        image: {
          x: this.original.crop.image.x,
          y: this.original.crop.image.y,
          width: this.original.crop.image.width,
          height: this.original.crop.image.height,
        },
      }

      this.updateCropper(true)
    },

    removePhoto() {
      this.setPicker('remove')

      this.source = null
    },

    fileSelected(files) {
      if (files.length === 0) {
        return this.resetPickerToCurrent()
      }

      this.setPicker('upload')
      this.file = files[0]
      this.uri = URL.createObjectURL(this.file)

      this.updateCropper()
    },

    setPhotoUri(uri, source) {
      if (this.activePicker === 'uri' && this.uri === uri) {
        return this.resetPickerToCurrent()
      }

      this.setPicker('uri')
      this.uriFrom = this.source = source
      this.uri = uri

      this.updateCropper()
    },

    labelText() {
      if (this.activePicker === 'upload') return this.file.name

      if (this.activePicker === 'uri') {
        if (this.uriFrom === 'discogs') return 'Wybrano z Discogsa'
        if (this.uriFrom === 'filmpolski') return 'Wybrano z FilmuPolskiego'
      }

      return 'Wybierz plik'
    },

    size() {
      if (this.activePicker !== 'upload') return

      return prettyBytes(this.file.size)
    },

    initCropper(src, onFaceInitialize, onInitialize) {
      this.croppers.face = null
      this.croppers.image = null

      const faceCropperContainer = this.artistForm.$refs.faceCropper
      const imageCropperContainer = this.artistForm.$refs.imageCropper

      faceCropperContainer.innerHTML = ''
      imageCropperContainer.innerHTML = ''

      const faceCropperEl = document.createElement('img')
      const imageCropperEl = document.createElement('img')

      faceCropperEl.setAttribute('src', src)
      imageCropperEl.setAttribute('src', src)

      faceCropperEl.onload = (event) => {
        this.croppers.face = new Cropper(event.target, {
          aspectRatio: 1,
          minSize: [50, 50, 'px'],
          startSize: [100, 100, '%'],
          onInitialize: onFaceInitialize,
          onCropMove: (crop) => (this.crop.face = crop),
          onCropEnd: (crop) => (this.crop.face = crop),
        })
      }
      imageCropperEl.onload = (event) => {
        this.croppers.image = new Cropper(event.target, {
          minSize: [50, 50, 'px'],
          startSize: [100, 100, '%'],
          onInitialize,
          onCropMove: (crop) => (this.crop.image = crop),
          onCropEnd: (crop) => (this.crop.image = crop),
        })
      }

      faceCropperContainer.appendChild(faceCropperEl)
      imageCropperContainer.appendChild(imageCropperEl)
    },

    updateCropper(resetCropToOriginal = false) {
      if (this.uri === null) return

      const onFaceInitialize = resetCropToOriginal
        ? (instance) => instance
          .resizeToScaled(this.original.crop.face.size, this.original.crop.face.size)
          .moveToScaled(this.original.crop.face.x, this.original.crop.face.y)
        : (instance) => instance

      const onInitialize = resetCropToOriginal
        ? (instance) => instance
          .resizeToScaled(this.original.crop.image.width, this.original.crop.image.height)
          .moveToScaled(this.original.crop.image.x, this.original.crop.image.y)
        : (instance) => instance

      this.initCropper(this.uri, onFaceInitialize, onInitialize)
    },
  }
}
