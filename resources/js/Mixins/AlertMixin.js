export default {
    methods: {
        async alertUser(title = "Remove",message = "Are You Sure",type = 'error')
        {
            return this.$alert(message,title,type);
        },
        async askUser(title = "Remove",message = "Are You Sure",type = 'error')
        {
            return this.$confirm(message,title,type,{});
        },
        async notifyUser(title = "Remove",message = "Are You Sure",type = 'error')
        {
            return this.$notify({
                title: title,
                message: message,
                type: type
            });
        },
        async messageUser(message = "Message",type = 'error')
        {
            return this.$message({
                message: message,
                type: type
            });
        },
    }
}
