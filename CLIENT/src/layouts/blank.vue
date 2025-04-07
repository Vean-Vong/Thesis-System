<!-- eslint-disable import/extensions -->
<!-- eslint-disable import/no-unresolved -->
<script>
import { useAuthStore } from '@/plugins/auth.module'
import api from '@/plugins/utilites'
import { useSkins } from '@core/composable/useSkins'

export default defineComponent({
  setup() {
    onMounted(() => {
      api.post('app-settings-verify').then(res => {
        if (res.status == 200) {
          authStore.setSettings(res.data.data)
        }
      })
    })
    const authStore = useAuthStore()
    const routerView = resolveComponent('router-view')
    const { injectSkinClasses } = useSkins()

    // ℹ️ This will inject classes in body tag for accurate styling
    injectSkinClasses()

    return () => h('div', { class: 'layout-wrapper layout-blank' }, h(routerView))
  },
})
</script>

<style>
.layout-wrapper.layout-blank {
  flex-direction: column;
}
</style>
