<template>
    <div class="container py-5 px-4">
  <!-- For demo purpose-->
  <header class="text-center my-5">
    <h1 class="display-4 text-white">Groovy Chat</h1>
  </header>

  <div class="row rounded-lg overflow-hidden shadow">
    <!-- Users box-->
    <Left></Left>
    <!-- Chat Box-->
    <Right></Right>
  </div>
</div>
</template>

<script>
import Left from './Left/Left.vue';
import Right from './Right/Right.vue';

    export default{ components: { Left, Right } }

</script>