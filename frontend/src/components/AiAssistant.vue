<template>
  <div class="ai-assistant">
    <v-app>
        <v-container>
            <h1>AI Assistant</h1>
        </v-container>
        <v-container>
            <v-text-field variant="solo" v-model="userPrompt" placeholder="How can I help?" />
            <v-switch
                v-model="tempChat"
                label="Temporary Chat"
                @change="handleToggle"
                color="primary"
            />
            <v-btn variant="tonal" @click="sendPrompt">Send</v-btn>

            <v-alert v-if="error" type="error" outlined>
                {{ error }}
            </v-alert>

            <v-progress-linear v-if="loading" indeterminate color="primary"></v-progress-linear>

            <v-list class="mt-4">
            <v-list-item v-for="(entry, index) in history" :key="index">
                <v-card outlined>
                <v-card-text>
                    <p><strong>You:</strong> {{ entry.prompt }}</p>
                    <p><strong>AI:</strong> {{ entry.response }}</p>
                </v-card-text>
                </v-card>
            </v-list-item>
            </v-list>
        </v-container>
    </v-app>
  </div>
</template>

<script>
import axios from 'axios';
axios.defaults.baseURL = 'http://127.0.0.1:8000';

export default {
  data() {
    return {
      userPrompt: '',
      history: [],
      loading: false,
      error: '',
      tempChat: false,
      response: undefined
    };
  },
  methods: {
    async sendPrompt() {
      if (!this.userPrompt.trim()) return;
      this.loading = true;
      this.error = '';
      try {
        this.response = await axios.post('/api/ai/assistant', { prompt: this.userPrompt, tempChat: this.tempChat }, {headers: {'Accept': 'application/json'}});
        const apiPrompt = this.response.data.prompt;
        this.history.unshift({ prompt: apiPrompt, response: this.response.data.reply });
        this.userPrompt = '';
      } catch (err) {
        this.error = 'Failed to fetch response';
      } finally {
        this.loading = false;
      }
    },
    handleToggle(value) {
        this.tempChat = value
    }
  }
};
</script>

<style scoped>
.ai-assistant { max-width: 400px; margin: auto; }
.history { margin-top: 10px; padding: 5px; border: 1px solid #ddd; }
.error { color: white; }
</style>
