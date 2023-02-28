import {ref} from "vue";

export default () => {
    let visible = ref(false)

    let show = () => visible.value = true

    let hide = () => visible.value = false

    let toggle = () => visible.value = !visible.value

    return {
        visible,
        show,
        hide,
        toggle
    }
}


