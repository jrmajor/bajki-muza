import prettyBytes from 'pretty-bytes'

export default function (data) {
  return {
    picker: 'current',

    source: data.photo.source,

    pickers: {
      current: {
        uri: data.photo.uri,
        source: data.photo.source
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

    removePhoto () {
      this.picker = 'remove'
      this.source = ''
    },

    resetPickerToCurrent () {
      this.picker = 'current'
      this.source = this.pickers.current.source
    },

    fileSelected (files, value) {
      if (value === '') return this.resetPickerToCurrent()

      this.pickers.uri.uri = ''

      this.picker = 'upload'
      this.pickers.upload.uri = URL.createObjectURL(files[0])
    },

    setPhotoUri (uri, source) {
      if (this.picker === 'uri' && this.pickers.uri.uri === uri) {
        return this.resetPickerToCurrent()
      }

      this.pickers.upload.file = ''

      this.picker = 'uri'
      this.pickers.uri.uri = uri
      this.pickers.uri.source = this.source = source
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
    }
  }
}
