import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '../css/app.css'
import { ZiggyVue } from 'ziggy'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

// PrimeVue CSS imports
import PrimeVue from 'primevue/config';
import 'primevue/resources/themes/aura-light-green/theme.css'
import 'primeicons/primeicons.css'
import 'primevue/resources/primevue.min.css'

// PrimeVue component imports
import Dialog from 'primevue/dialog';
import Button from "primevue/button"
import InputText from 'primevue/inputtext';
import InputMask from 'primevue/inputmask';
import Image from 'primevue/image';
import FileUpload from 'primevue/fileupload';
import ProgressSpinner from 'primevue/progressspinner';



createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(PrimeVue)
      .use(VueSweetalert2)
      .component('Dialog',Dialog)
      .component('Button',Button)
      .component('InputText',InputText)
      .component('InputMask',InputMask)
      .component('Image', Image)
      .component('FileUpload',FileUpload)
      .component('ProgressSpinner',ProgressSpinner)
      .mount(el)
  },
})