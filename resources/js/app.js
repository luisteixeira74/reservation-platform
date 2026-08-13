import Alpine from "alpinejs";
import { createApp } from "vue";
import NotificationBell from "./components/NotificationBell.vue";

window.Alpine = Alpine;

Alpine.start();

const notificationBell = document.querySelector("#notification-bell");

if (notificationBell) {
    createApp(NotificationBell).mount("#notification-bell");
}
