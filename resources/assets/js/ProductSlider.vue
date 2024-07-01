<template>
    <div 
        class="slideChanger"
        @click="changeSlide"
    >
        <span>{{ notNull === false ? `About ${name}` : 'Back to Product Image' }}</span>
    </div>
</template>
<script>
    export default {
        name:'productSlider',
		props:{
            flickity:{
                type:Object,
                default:()=>({})
            },
            name:{
                type:String,
                default:''
            },
            index:{
                type:Number,
                default:0
            }
        },
        watch:{
            index(idx) {
                if(idx === 0) {
                    this.notNull = false
                }else {
                    this.notNull = true
                }
            }
        },
        data() {
            return {
                notNull:false,
            }
        },
        methods:{
            changeSlide() {
				if(this.flickity.selectedIndex === 0) {
					this.flickity.select(1)
					this.notNull = true
				}else {
					this.flickity.select(0)
					this.notNull = false
				}
			},
        }
	}
</script>