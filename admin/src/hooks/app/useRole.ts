import { ref } from 'vue'
import { type RoleItem, getRoleList as getRoleListApi } from '@/apis/permission'

/** 角色模块 */
export function useRole() {
  const loading = ref(false)
  const roleList = ref<RoleItem[]>([])
  const total = ref(0)
  const getRoleList = async () => {
    try {
      loading.value = true
      const res = await getRoleListApi({ page: 1, size: 99 })
      roleList.value = res.data.data.filter((i) => i.status === 1)
      total.value = res.data.total
    } finally {
      loading.value = false
    }
  }
  return { roleList, getRoleList, loading, total }
}
