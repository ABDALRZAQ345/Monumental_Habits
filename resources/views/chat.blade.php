<x-slot name="header">
    <h2 class="text-xl font-semibold">{{ __('Chat') }}</h2>
</x-slot>

<div class="py-12 max-w-4xl mx-auto bg-white shadow-sm rounded-lg p-6">
    <!-- Message Display Section -->
    <div id="messages" class="mb-4 space-y-2 p-2 border rounded bg-gray-100 h-64 overflow-y-auto">
        <!-- Messages will be appended here -->
    </div>

    <!-- Message Form -->
    <form id="sendMessageForm">
        @csrf
        <textarea id="message" required class="w-full border rounded p-2 text-gray-800"></textarea>
        <button type="submit" class="mt-2 px-4 py-2 bg-blue-500  rounded text-gray-800">Send</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const messagesContainer = document.getElementById('messages');

        // Listen for new messages via Laravel Echo
        Echo.channel('chat').listen('NewMessage', (event) => {
            const messageElement = document.createElement('div');
            messageElement.classList.add('p-2', 'bg-white', 'rounded', 'shadow','font-bold','text-gray-800');
            messageElement.textContent = event.message;
            messagesContainer.appendChild(messageElement);

            // Scroll to the latest message
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        });

        document.getElementById('sendMessageForm').addEventListener('submit', (e) => {
            e.preventDefault();

            const messageInput = document.getElementById('message');
            const message = messageInput.value.trim();

            if (!message) return; // Prevent sending empty messages

            // Send message via fetch API
            fetch('{{ route('send.message') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ message })
            })
                .then(response => response.json())
                .then(data => {
                    messageInput.value = ''; // Clear input after sending
                })
                .catch(error => console.error('Failed:', error));
        });
    });
</script>
