<template>
    <!-- TODO; Add this class when conversation selected (active) -->
            <!-- active text-white -->
            <a class="list-group-item list-group-item-action  rounded-0">
              <div class="media"><img src="https://bootstrapious.com/i/snippets/sn-chat/avatar.svg" alt="user" width="50" class="rounded-circle">
                <div class="media-body ml-4">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <h6 class="mb-0">{{conversation.username }}</h6><small class="small font-weight-bold">{{ date }}</small>
                  </div>
                  <p class="font-italic mb-0 text-small">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore.</p>
                </div>
              </div>
            </a>

</template>


<script>
    export default {
      props:{
        conversation: Object
      },
      computed: {
        date(){
          return new Date(this.conversation.createdAt).toLocaleTimeString();
        }
      }
    }
</script>