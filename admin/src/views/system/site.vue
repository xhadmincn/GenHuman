<template>
    <div class="gi_full_column gi_margin gi_bg_1">
        <a-card title="站点配置">
            <a-form layout="vertical" style="max-width: 800px;" size="small" :model="form">
                <a-form-item label="站点LOGO">
                    <YUpload v-model="form.webLogo"></YUpload>
                </a-form-item>
                <a-form-item label="站点名称">
                    <a-input placeholder="请输入站点名称" v-model="form.webName" />
                </a-form-item>
                <a-form-item label="站点域名">
                    <a-input placeholder="请输入站点域名" v-model="form.webUrl" />
                </a-form-item>
                <a-form-item>
                    <a-button type="primary" @click="submit">保存</a-button>
                </a-form-item>
            </a-form>
        </a-card>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { SiteFormInterface } from './type';
import { getConfigData, saveConfig } from '@/apis/system'
import { Message } from '@arco-design/web-vue'
const form = ref<SiteFormInterface>({
    webName: '',
    webLogo: '',
    webUrl: ''
});

//获取配置
const getConfig = async () => {
    const data = await getConfigData({ key: 'site' })
    if (data.data) {
        form.value = { ...data.data }
    }
}

getConfig()
const submit = () => {
    const data = form.value
    saveConfig({ key: 'site', value: data }).then((res: any) => {
        if (res.code == 200) {
            Message.success('保存成功')
        } else {
            Message.error(res.msg)
        }
    }).catch(err => {
        Message.error(err)
    })
}
</script>