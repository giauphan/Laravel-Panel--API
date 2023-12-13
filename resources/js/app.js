import { createApp } from 'vue'

const app = createApp({})

import ExampleComponent from './components/ExampleComponent.vue'
import DropDown from './components/Drop-down.vue'
import LinkItem from './components/List-GroupItemCustorm.vue'
import paginate from './components/paginate.vue'
import Paginate from 'vuejs-paginate'

app.component('paginate', Paginate)
app.component('example-component', ExampleComponent)
app.component('drop-down', DropDown)
app.component('link-item', LinkItem)
app.component('paginate-page', paginate)

app.mount('#app')
