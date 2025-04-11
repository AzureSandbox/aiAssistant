<template>
    <div>
        <!-- <h1>AI Assistant</h1> -->
        <AiAssistant />
    </div>
</template>

<script>
import AiAssistant from './components/AiAssistant.vue';

export default {
  name: 'App',
  components: {
    AiAssistant
  }
}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
