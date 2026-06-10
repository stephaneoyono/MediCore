import { createApp } from "vue";
import Home from "./pages/Home.vue";
import LoginComponent from "./components/LoginComponent.vue";
import RegisterComponent from "./components/RegisterComponent.vue";

// Exporter les composants pour utilisation dans les fichiers Blade
window.Vue = {
  createApp,
  Home,
  LoginComponent,
  RegisterComponent
};