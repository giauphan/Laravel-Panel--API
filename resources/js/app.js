import { createApp } from 'vue'

const app = createApp({})

import ExampleComponent from './components/ExampleComponent.vue'
import DropDown from './components/Drop-down.vue'
import LinkItem from './components/List-GroupItemCustorm.vue'
import paginate from './components/paginate.vue'
import seach from './components/seach.vue'
import Modal from './components/ModalComponent.vue'
import UploadFile from './Page/UploadFile/index.vue'

app.component('example-component', ExampleComponent)
app.component('drop-down', DropDown)
app.component('link-item', LinkItem)
app.component('paginate-page', paginate)
app.component('input-search', seach)
app.component('modal-component', Modal)
app.component('updaload-file', UploadFile)

app.mount('#app')
