<template>
  <AppLayout title="Chat">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Chat</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div>
            <div class="card card-primary card-outline direct-chat direct-chat-primary overflow-y-scroll max-h-80" ref="hasScrolledToBottom">
              <div v-for="message in messages" :key="message.created_at" class="card-body" style="display: block">
                <div class="direct-chat-messages p-5">
                  <template v-if="$page.props.auth.user.id != message.user_id">
                    <div class="direct-chat-msg left">
                      <div class="flex justify-between">
                        <span class="direct-chat-name float-left">{{ message.user_id }}</span>
                        <span class="direct-chat-timestamp float-right">{{ message.created_at }}</span>
                      </div>
                      <div class="flex justify-start items-center">
                        <img class="w-10 h-10 rounded-full mr-2" src="@img/avatar/Author-Clyde-Webflow.png" alt="Message User Image" />

                        <div class="direct-chat-text bg-slate-200 w-full p-4 rounded">{{ message.text }}</div>
                      </div>
                    </div>
                  </template>

                  <template v-else>
                    <div class="direct-chat-msg right mt-5">
                      <div class="flex justify-between">
                        <span class="direct-chat-timestamp float-left">{{ message.created_at }}</span>
                        <span class="direct-chat-name float-right">{{ message.user_id }}</span>
                      </div>
                      <div class="flex justify-end items-center">
                        <div class="direct-chat-text bg-slate-200 w-full p-4 rounded">{{ message.text }}</div>
                        <img class="w-10 h-10 rounded-full ml-2" src="@img/avatar/Author-Wumpus-Webflow.png" alt="Message User Image" />
                      </div>
                    </div>
                  </template>
                </div>
              </div>

              <div class="card-footer" style="display: block">
                <form @submit.prevent="submit">
                  <div class="input-group flex relative">
                    <input v-model="form.text" type="text" placeholder="Type Message ..." class="block w-full rounded-md border-0 py-1.5 pl-7 pr-20 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <span class="input-group-append absolute end-2">
                      <button @click="scrollBottom" type="submit" class="bg-black text-white p-2" :disabled="!form.text">Send</button>
                    </span>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'

import { useForm } from '@inertiajs/vue3'
import { ref, onMounted, onUnmounted, onUpdated } from 'vue'

const props = defineProps({
  messages: Object
})

const form = useForm({
  text: ''
})

function submit(params) {
  form.post('/chat', {
    replace: false,
    preserveState: false,
    preserveScroll: false,
    only: [],
    headers: {},
    errorBag: null,
    forceFormData: false,
    onCancelToken: (cancelToken) => {},
    onCancel: () => {},
    onBefore: (visit) => {},
    onStart: (visit) => {},
    onProgress: (progress) => {},
    onSuccess: (page) => {
      form.reset()
      scrollBottom()
    },
    onError: (errors) => {},
    onFinish: (visit) => {}
  })
}

const fetching = ref('')
const messages = ref(props.messages)

function fetchMessages(params) {
  fetching.value = setInterval(function () {
    axios.get('/chat/101').then((response) => {
      messages.value = response.data
    })
  }, 3000)
}

const hasScrolledToBottom = ref(null)

const scrollBottom = () => {
  if (messages.value.length > 1) {
    let el = hasScrolledToBottom.value
    el.scrollTop = el.scrollHeight
  }
}

onUpdated(() => {
  scrollBottom()
})

onMounted(() => {
  scrollBottom()
  fetchMessages()
})

onUnmounted(() => {
  clearInterval(fetching.value)
})
</script>

<style scoped>
/** */
</style>
