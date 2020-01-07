/**
 * This vue component is for the subscription video upload button, 
 * that an authenticated user can make use of by visiting their own channel profile.
 * Within this component we make sure that the uploaded video files are stored in
 * the database with the right path and name.
 */

Vue.component('channel-uploads', {

    // We pass data to the child component from the parent by using props.
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        }
    },

    data: () => ({
        selected: false,
        videos: [],
        progress: {}
    }),

    /**
     * We want to send each video to the server with axios.post, and then get the promise returned from axios.
     * By using the uploaders array we can tell when all the videos are done uploading to the server, so that the
     * video conversion can start.
     */
    methods: {
        upload() {
            this.selected = true
            this.videos = Array.from(this.$refs.videos.files)
            
            /**
             * We map over each video then we create a new form and append the video and title then make a
             * post request with axios to the server to save the form.
             */
            const uploaders = this.videos.map(video => {
                const form = new FormData()

                this.progress[video.name] = 0

                form.append('video', video)
                form.append('title', video.name)

                return axios.post(`/channels/${this.channel.id}/videos`, form, {
                    
                    /**
                     * We make use of the progressevent to calculate/know what the current progress is of the uploaded file,
                     * so the user can be informed with a visual progressbar.
                     */
                    onUploadProgress: (event) => {
                        this.progress[video.name] = Math.ceil((event.loaded / event.total) * 100) 

                        // It can occur that the progress object doesn't update so we force it to do so.
                        this.$forceUpdate()
                    }
                })
            })
        }
    }
})