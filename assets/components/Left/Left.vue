<template>
    <div class="col-5 px-0">
      <div class="bg-white">

        <div class="bg-gray px-4 py-2 bg-light">
          <p class="h5 mb-0 py-1">Recent</p>
        </div>

        <div class="messages-box">
          <div class="list-group rounded-0">
                <Conversation :conversation="conversation"/>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex';
    import Conversation from './Conversation.vue';

    export default {
      
        components: {Conversation},
        computed: {
          ...mapGetters(["CONVERSATIONS"])
        },
        mounted(){
          this.$store.dispatch("GET_CONVERSATIONS")
            .then()
        }
    }
</script>