import numeral from 'numeral'

/**
 * This vue component is for the subscription button that appears on a users channel.
 * Within this component we check which user is able to subscribe or unsubscribe to a channel.
 */

Vue.component('subscribe-button', {

    // We pass data to the child component from the parent by using props.
    props: {
        channel: {
            type: Object,
            required: true,
            default: () => ({})
        },

        initialSubscriptions: {
            type: Array,
            required: true,
            default: () => []
        }
    },

    /** 
    * initialSubscriptions can't be modified because it comes in as a prop, so the DOM can't be updated.
    * This data property makes sure that it can be modified.
    */
    data: function() {
        return {
            subscriptions: this.initialSubscriptions
        }
    },

    /**
     * We make use of computed properties to keep track of which values are changing and then running the associated method. 
     * This prevents all methods from running at once, which is not what we want. 
     */
    computed: {
        subscribed() {
            if (! __auth() || this.channel.user_id === __auth().id) return false

            return !!this.subscription
        },

        owner() {
            if (__auth() && this.channel.user_id === __auth().id) return true

            return false
        },

        subscription() {
            if (! __auth()) return null

            return this.subscriptions.find(subscription => subscription.user_id === __auth().id)
        },

        count() {
            return numeral(this.subscriptions.length).format('0a')
        }
    },

    /**
     * We want to do some checks to make sure that the user can make use of the subscription button.
     * These checks are defined in the computed property.
     */
    methods: {
        toggleSubscription() {
            if (! __auth()){
                return alert('Please login to subscribe!')
            }

            if (this.owner) {
                return alert('You can\'t subscribe to your own channel')
            }

            if (this.subscribed) {
                axios.delete(`/channels/${this.channel.id}/subscriptions/${this.subscription.id}`)
                    .then(() => {
                        this.subscriptions = this.subscriptions.filter(s => s.id != this.subscription.id)
                    })
            } else {
                axios.post(`/channels/${this.channel.id}/subscriptions`)
                    .then(response => {
                        this.subscriptions = [
                            ...this.subscriptions,
                            response.data
                        ]

                    })
            }
        }
    }
})