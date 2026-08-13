<template>
    <div class="relative">
        <button
            type="button"
            class="relative inline-flex h-9 w-9 items-center justify-center"
            @click="toggleNotifications"
        >
            <span class="text-xl">🔔</span>

            <span
                v-if="unreadCount > 0"
                class="absolute -right-2 -top-2 flex h-[18px] w-[18px] items-center justify-center rounded-full bg-red-600 border-2 border-white text-[9px] font-bold leading-none text-white shadow-sm"
            >
                {{ unreadCount }}
            </span>
        </button>

        <div
            v-if="isOpen"
            class="absolute right-0 mt-2 w-80 bg-white border rounded-lg shadow-lg z-50"
        >
            <div class="p-4 font-semibold border-b">Notifications</div>

            <div v-if="loading" class="p-4 text-sm text-gray-500">
                Loading...
            </div>

            <template v-else>
                <div
                    v-for="notification in notifications"
                    :key="notification.id"
                    :class="[
                        'p-4 border-b cursor-pointer',
                        notification.read_at
                            ? 'bg-white'
                            : 'bg-gray-50 font-semibold',
                    ]"
                    @click="markAsRead(notification)"
                >
                    <div>
                        <span v-if="!notification.read_at" class="mr-2">
                            ●
                        </span>

                        <span>
                            {{ notification.title }}
                        </span>
                    </div>

                    <div class="text-sm text-gray-600">
                        {{ notification.message }}
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from "vue";

const isOpen = ref(false);
const notifications = ref([]);
const loading = ref(false);
const unreadCount = ref(0);

let unreadPolling = null;

onMounted(() => {
    loadUnreadCount();

    unreadPolling = setInterval(() => {
        loadUnreadCount();
    }, 6000);
});

onUnmounted(() => {
    clearInterval(unreadPolling);
});

onUnmounted(() => {
    console.log("NotificationBell unmounted");
    clearInterval(unreadPolling);
});

const loadNotifications = async () => {
    loading.value = true;

    try {
        const response = await fetch("/notifications", {
            headers: {
                Accept: "application/json",
            },
        });

        const data = await response.json();

        notifications.value = data.notifications;
    } finally {
        loading.value = false;
    }
};

const toggleNotifications = async () => {
    isOpen.value = !isOpen.value;

    if (isOpen.value) {
        await loadNotifications();
    }
};

const loadUnreadCount = async () => {
    try {
        const response = await fetch("/notifications/unread-count", {
            headers: {
                Accept: "application/json",
            },
        });

        const data = await response.json();

        unreadCount.value = data.unread_count;
    } catch (error) {
        console.error("Failed to load unread count:", error);
    }
};

const markAsRead = async (notification) => {
    if (notification.read_at) {
        return;
    }

    console.log("ANTES:", notification.read_at);

    const response = await fetch(`/notifications/${notification.id}/read`, {
        method: "PATCH",
        headers: {
            Accept: "application/json",
            "X-CSRF-TOKEN": document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content"),
        },
    });

    console.log("STATUS:", response.status);

    if (!response.ok) {
        return;
    }

    notification.read_at = new Date().toISOString();

    if (unreadCount.value > 0) {
        unreadCount.value--;
    }

    console.log("DEPOIS:", notification.read_at);
};
</script>
