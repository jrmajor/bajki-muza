import './artistPicker'
import axios from 'axios'

window.taleFormData = function (data) {
  return {
    cover: {
      preview: '',
      file: '',
      remove: 0,
    },
    lyricists: data.lyricists.map(lyricist => ({
      artist: lyricist,
      key: Math.random().toString(20).substr(2),
    })),
    composers: data.composers.map(composer => ({
      artist: composer,
      key: Math.random().toString(20).substr(2),
    })),
    actors: data.actors.map(actor => {
      actor.key = Math.random().toString(20).substr(2)
      return actor
    }),

    setCoverPreview(files) {
      if (files.length == 0) {
        this.cover.preview = ''
      } else {
        this.cover.preview = URL.createObjectURL(files[0])
      }
    },

    fileSize(size) {
      if(size < 1024) {
        return size + 'bytes';
      } else if(size >= 1024 && size < 1048576) {
        return (size/1024).toFixed(1) + 'KB';
      } else if(size >= 1048576) {
        return (size/1048576).toFixed(1) + 'MB';
      }
    },

    addArtist(type) {
      this[type].push({
        artist: '',
        key: Math.random().toString(20).substr(2),
      })
    },

    removeArtist(type, index) {
      this[type].splice(index, 1)
    },
  }
}
