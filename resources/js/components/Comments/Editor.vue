<template>
    <div>
        <b-card no-body>
            <b-tabs card>
                <b-tab title="Edit" active>
                    <b-form-textarea
                        id="content"
                        v-model.trim="form.comment"
                        placeholder="Enter something..."
                        rows="3"
                        max-rows="6"
                    ></b-form-textarea>
                </b-tab>
                <b-tab title="Preview">
                    <b-card-text v-html="marked(form.comment?form.comment:'')"></b-card-text>
                </b-tab>
            </b-tabs>
        </b-card>
        <b-button block variant="outline-primary" @click="reply">Comment</b-button>
    </div>
</template>

<script>
export default {

}
</script>

<style>

</style>