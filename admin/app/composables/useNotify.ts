type NotifyOptions = {
  title?: string;
  description?: string;
};

export const useNotify = () => {
  const toast = useToast();

  const success = (options: NotifyOptions = {}) => {
    toast.add({
      title: options.title ?? "Success",
      description: options.description,
      color: "success",
      icon: "i-lucide-circle-check",
    });
  };

  const error = (options: NotifyOptions = {}) => {
    toast.add({
      title: options.title ?? "Something went wrong",
      description: options.description,
      color: "error",
      icon: "i-lucide-circle-x",
    });
  };

  const warning = (options: NotifyOptions = {}) => {
    toast.add({
      title: options.title ?? "Warning",
      description: options.description,
      color: "warning",
      icon: "i-lucide-triangle-alert",
    });
  };

  const info = (options: NotifyOptions = {}) => {
    toast.add({
      title: options.title ?? "Info",
      description: options.description,
      color: "info",
      icon: "i-lucide-info",
    });
  };

  return {
    success,
    error,
    warning,
    info,
  };
};
