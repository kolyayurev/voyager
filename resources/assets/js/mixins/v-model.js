export default {
  props: {
    value: {
    //   type: [String, Number, Boolean, Array, Object, Date, Function, Symbol],
      validator: v => true,
      required: true,
    },
  },
  computed: {
    localValue: {
      get() {
        return this.value;
      },
      set(localState) {
        this.$emit('input', localState);
      },
    },
  },
};
