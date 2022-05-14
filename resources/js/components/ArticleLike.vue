<template>
    <div>
        <button
            type="button"
            class="btn m-0 p-1 shadow-none"
            @click="clickLike"
        >
            <i
                class="fas fa-heart mr-1"
                :class="{
                    'red-text': this.isLikedBy,
                    'animated heartBeat fast': this.gotToLike,
                }"
            ></i>
        </button>
        {{ countLikes }}
    </div>
</template>

<script>
export default {
    props: {
        initialIsLikedBy: {
            type: Boolean,
            default: false,
        },
        initialCountLikes: {
            type: Number,
            default: 0,
        },
        authorized: {
            type: Boolean,
            default: false,
        },
        endpoint: {
            type: String,
        },
    },
    data() {
        return {
            isLikedBy: this.initialIsLikedBy,
            countLikes: this.initialCountLikes,
            gotToLike: false,
        };
    },
    methods: {
        clickLike() {
            if (!this.authorized) {
                alert("いいね機能はログイン中のみ使用できます");
                return;
            }
            // ログイン中のユーザーがlikesテーブルにいるかどうか
            this.isLikedBy ? this.unlike() : this.like();
        },
        // asyncを使用し非同期処理であるput/deleteメソッドを、コールバックやPromiceを使用せず同期的に実行する
        async like() {
            const response = await axios.put(this.endpoint);

            // Bladeから渡された情報を上書きする
            this.isLikedBy = true;

            // responseには記事idと変更後のいいね数がJson形式で返っている
            // .dataでJsonのボディー部分を取得可能
            this.countLikes = response.data.countLikes;
            this.gotToLike = true;
        },
        async unlike() {
            const response = await axios.delete(this.endpoint);

            // Bladeから渡された情報を上書きする
            this.isLikedBy = false;

            // responseには記事idと変更後のいいね数がJson形式で返っている
            this.countLikes = response.data.countLikes;
            this.gotToLike = false;
        },
    },
};
</script>
