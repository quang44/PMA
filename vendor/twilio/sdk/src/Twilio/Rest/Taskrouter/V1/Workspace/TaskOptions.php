<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace;

use Twilio\Options;
use Twilio\Values;

abstract class TaskOptions {
    /**
     * @param string $attributes The JSON string that describes the custom
     *                           attributes of the task
     * @param string $assignmentStatus The new status of the task
     * @param string $reason The reason that the Task was canceled or complete
     * @param int $priority The Task's new priority value
     * @param string $taskChannel When MultiTasking is enabled, specify the
     *                            TaskChannel with the task to update
     * @param string $ifMatch The If-Match HTTP request header
     * @return UpdateTaskOptions Options builder
     */
    public static function update(string $attributes = Values::NONE, string $assignmentStatus = Values::NONE, string $reason = Values::NONE, int $priority = Values::NONE, string $taskChannel = Values::NONE, string $ifMatch = Values::NONE): UpdateTaskOptions {
        return new UpdateTaskOptions($attributes, $assignmentStatus, $reason, $priority, $taskChannel, $ifMatch);
    }

    /**
     * @param string $ifMatch The If-Match HTTP request header
     * @return DeleteTaskOptions Options builder
     */
    public static function delete(string $ifMatch = Values::NONE): DeleteTaskOptions {
        return new DeleteTaskOptions($ifMatch);
    }

    /**
     * @param int $priority The priority value of the Tasks to read
     * @param string[] $assignmentStatus Returns the list of all Tasks in the
     *                                   Workspace with the specified
     *                                   assignment_status
     * @param string $workflowSid The SID of the Workflow with the Tasks to read
     * @param string $workflowName The friendly name of the Workflow with the Tasks
     *                             to read
     * @param string $taskQueueSid The SID of the TaskQueue with the Tasks to read
     * @param string $taskQueueName The friendly_name of the TaskQueue with the
     *                              Tasks to read
     * @param string $evaluateTaskAttributes The task attributes of the Tasks to
     *                                       read
     * @param string $ordering Controls the order of the Tasks returned
     * @param bool $hasAddons Whether to read Tasks with addons
     * @return ReadTaskOptions Options builder
     */
    public static function read(int $priority = Values::NONE, array $assignmentStatus = Values::ARRAY_NONE, string $workflowSid = Values::NONE, string $workflowName = Values::NONE, string $taskQueueSid = Values::NONE, string $taskQueueName = Values::NONE, string $evaluateTaskAttributes = Values::NONE, string $ordering = Values::NONE, bool $hasAddons = Values::NONE): ReadTaskOptions {
        return new ReadTaskOptions($priority, $assignmentStatus, $workflowSid, $workflowName, $taskQueueSid, $taskQueueName, $evaluateTaskAttributes, $ordering, $hasAddons);
    }

    /**
     * @param int $timeout The amount of time in seconds the task can live before
     *                     being assigned
     * @param int $priority The priority to assign the new task and override the
     *                      default
     * @param string $taskChannel When MultiTasking is enabled specify the
     *                            TaskChannel by passing either its unique_name or
     *                            SID
     * @param string $workflowSid The SID of the Workflow that you would like to
     *                            handle routing for the new Task
     * @param string $attributes A URL-encoded JSON string describing the
     *                           attributes of the task
     * @return CreateTaskOptions Options builder
     */
    public static function create(int $timeout = Values::NONE, int $priority = Values::NONE, string $taskChannel = Values::NONE, string $workflowSid = Values::NONE, string $attributes = Values::NONE): CreateTaskOptions {
        return new CreateTaskOptions($timeout, $priority, $taskChannel, $workflowSid, $attributes);
    }
}

class UpdateTaskOptions extends Options {
    /**
     * @param string $attributes The JSON string that describes the custom
     *                           attributes of the task
     * @param string $assignmentStatus The new status of the task
     * @param string $reason The reason that the Task was canceled or complete
     * @param int $priority The Task's new priority value
     * @param string $taskChannel When MultiTasking is enabled, specify the
     *                            TaskChannel with the task to update
     * @param string $ifMatch The If-Match HTTP request header
     */
    public function __construct(string $attributes = Values::NONE, string $assignmentStatus = Values::NONE, string $reason = Values::NONE, int $priority = Values::NONE, string $taskChannel = Values::NONE, string $ifMatch = Values::NONE) {
        $this->options['attributes'] = $attributes;
        $this->options['assignmentStatus'] = $assignmentStatus;
        $this->options['reason'] = $reason;
        $this->options['priority'] = $priority;
        $this->options['taskChannel'] = $taskChannel;
        $this->options['ifMatch'] = $ifMatch;
    }

    /**
     * The JSON string that describes the custom attributes of the task.
     *
     * @param string $attributes The JSON string that describes the custom
     *                           attributes of the task
     * @return $this Fluent Builder
     */
    public function setAttributes(string $attributes): self {
        $this->options['attributes'] = $attributes;
        return $this;
    }

    /**
     * The new status of the task. Can be: `canceled`, to cancel a Task that is currently `pending` or `reserved`; `wrapping`, to move the Task to wrapup state; or `completed`, to move a Task to the completed state.
     *
     * @param string $assignmentStatus The new status of the task
     * @return $this Fluent Builder
     */
    public function setAssignmentStatus(string $assignmentStatus): self {
        $this->options['assignmentStatus'] = $assignmentStatus;
        return $this;
    }

    /**
     * The reason that the Task was canceled or completed. This parameter is required only if the Task is canceled or completed. Setting this value queues the task for deletion and logs the reason.
     *
     * @param string $reason The reason that the Task was canceled or complete
     * @return $this Fluent Builder
     */
    public function setReason(string $reason): self {
        $this->options['reason'] = $reason;
        return $this;
    }

    /**
     * The Task's new priority value. When supplied, the Task takes on the specified priority unless it matches a Workflow Target with a Priority set. Value can be 0 to 2^31^ (2,147,483,647).
     *
     * @param int $priority The Task's new priority value
     * @return $this Fluent Builder
     */
    public function setPriority(int $priority): self {
        $this->options['priority'] = $priority;
        return $this;
    }

    /**
     * When MultiTasking is enabled, specify the TaskChannel with the task to update. Can be the TaskChannel's SID or its `unique_name`, such as `voice`, `sms`, or `default`.
     *
     * @param string $taskChannel When MultiTasking is enabled, specify the
     *                            TaskChannel with the task to update
     * @return $this Fluent Builder
     */
    public function setTaskChannel(string $taskChannel): self {
        $this->options['taskChannel'] = $taskChannel;
        return $this;
    }

    /**
     * If provided, applies this mutation if (and only if) the [ETag](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/ETag) header of the Task matches the provided value. This matches the semantics of (and is implemented with) the HTTP [If-Match header](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/If-Match).
     *
     * @param string $ifMatch The If-Match HTTP request header
     * @return $this Fluent Builder
     */
    public function setIfMatch(string $ifMatch): self {
        $this->options['ifMatch'] = $ifMatch;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Taskrouter.V1.UpdateTaskOptions ' . $options . ']';
    }
}

class DeleteTaskOptions extends Options {
    /**
     * @param string $ifMatch The If-Match HTTP request header
     */
    public function __construct(string $ifMatch = Values::NONE) {
        $this->options['ifMatch'] = $ifMatch;
    }

    /**
     * If provided, deletes this Task if (and only if) the [ETag](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/ETag) header of the Task matches the provided value. This matches the semantics of (and is implemented with) the HTTP [If-Match header](https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/If-Match).
     *
     * @param string $ifMatch The If-Match HTTP request header
     * @return $this Fluent Builder
     */
    public function setIfMatch(string $ifMatch): self {
        $this->options['ifMatch'] = $ifMatch;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Taskrouter.V1.DeleteTaskOptions ' . $options . ']';
    }
}

class ReadTaskOptions extends Options {
    /**
     * @param int $priority The priority value of the Tasks to read
     * @param string[] $assignmentStatus Returns the list of all Tasks in the
     *                                   Workspace with the specified
     *                                   assignment_status
     * @param string $workflowSid The SID of the Workflow with the Tasks to read
     * @param string $workflowName The friendly name of the Workflow with the Tasks
     *                             to read
     * @param string $taskQueueSid The SID of the TaskQueue with the Tasks to read
     * @param string $taskQueueName The friendly_name of the TaskQueue with the
     *                              Tasks to read
     * @param string $evaluateTaskAttributes The task attributes of the Tasks to
     *                                       read
     * @param string $ordering Controls the order of the Tasks returned
     * @param bool $hasAddons Whether to read Tasks with addons
     */
    public function __construct(int $priority = Values::NONE, array $assignmentStatus = Values::ARRAY_NONE, string $workflowSid = Values::NONE, string $workflowName = Values::NONE, string $taskQueueSid = Values::NONE, string $taskQueueName = Values::NONE, string $evaluateTaskAttributes = Values::NONE, string $ordering = Values::NONE, bool $hasAddons = Values::NONE) {
        $this->options['priority'] = $priority;
        $this->options['assignmentStatus'] = $assignmentStatus;
        $this->options['workflowSid'] = $workflowSid;
        $this->options['workflowName'] = $workflowName;
        $this->options['taskQueueSid'] = $taskQueueSid;
        $this->options['taskQueueName'] = $taskQueueName;
        $this->options['evaluateTaskAttributes'] = $evaluateTaskAttributes;
        $this->options['ordering'] = $ordering;
        $this->options['hasAddons'] = $hasAddons;
    }

    /**
     * The priority value of the Tasks to read. Returns the list of all Tasks in the Workspace with the specified priority.
     *
     * @param int $priority The priority value of the Tasks to read
     * @return $this Fluent Builder
     */
    public function setPriority(int $priority): self {
        $this->options['priority'] = $priority;
        return $this;
    }

    /**
     * The `assignment_status` of the Tasks you want to read. Can be: `pending`, `reserved`, `assigned`, `canceled`, `wrapping`, or `completed`. Returns all Tasks in the Workspace with the specified `assignment_status`.
     *
     * @param string[] $assignmentStatus Returns the list of all Tasks in the
     *                                   Workspace with the specified
     *                                   assignment_status
     * @return $this Fluent Builder
     */
    public function setAssignmentStatus(array $assignmentStatus): self {
        $this->options['assignmentStatus'] = $assignmentStatus;
        return $this;
    }

    /**
     * The SID of the Workflow with the Tasks to read. Returns the Tasks controlled by the Workflow identified by this SID.
     *
     * @param string $workflowSid The SID of the Workflow with the Tasks to read
     * @return $this Fluent Builder
     */
    public function setWorkflowSid(string $workflowSid): self {
        $this->options['workflowSid'] = $workflowSid;
        return $this;
    }

    /**
     * The friendly name of the Workflow with the Tasks to read. Returns the Tasks controlled by the Workflow identified by this friendly name.
     *
     * @param string $workflowName The friendly name of the Workflow with the Tasks
     *                             to read
     * @return $this Fluent Builder
     */
    public function setWorkflowName(string $workflowName): self {
        $this->options['workflowName'] = $workflowName;
        return $this;
    }

    /**
     * The SID of the TaskQueue with the Tasks to read. Returns the Tasks waiting in the TaskQueue identified by this SID.
     *
     * @param string $taskQueueSid The SID of the TaskQueue with the Tasks to read
     * @return $this Fluent Builder
     */
    public function setTaskQueueSid(string $taskQueueSid): self {
        $this->options['taskQueueSid'] = $taskQueueSid;
        return $this;
    }

    /**
     * The `friendly_name` of the TaskQueue with the Tasks to read. Returns the Tasks waiting in the TaskQueue identified by this friendly name.
     *
     * @param string $taskQueueName The friendly_name of the TaskQueue with the
     *                              Tasks to read
     * @return $this Fluent Builder
     */
    public function setTaskQueueName(string $taskQueueName): self {
        $this->options['taskQueueName'] = $taskQueueName;
        return $this;
    }

    /**
     * The attributes of the Tasks to read. Returns the Tasks that match the attributes specified in this parameter.
     *
     * @param string $evaluateTaskAttributes The task attributes of the Tasks to
     *                                       read
     * @return $this Fluent Builder
     */
    public function setEvaluateTaskAttributes(string $evaluateTaskAttributes): self {
        $this->options['evaluateTaskAttributes'] = $evaluateTaskAttributes;
        return $this;
    }

    /**
     * How to order the returned Task resources. y default, Tasks are sorted by ascending DateCreated. This value is specified as: `Attribute:Order`, where `Attribute` can be either `Priority` or `DateCreated` and `Order` can be either `asc` or `desc`. For example, `Priority:desc` returns Tasks ordered in descending order of their Priority. Multiple sort orders can be specified in a comma-separated list such as `Priority:desc,DateCreated:asc`, which returns the Tasks in descending Priority order and ascending DateCreated Order.
     *
     * @param string $ordering Controls the order of the Tasks returned
     * @return $this Fluent Builder
     */
    public function setOrdering(string $ordering): self {
        $this->options['ordering'] = $ordering;
        return $this;
    }

    /**
     * Whether to read Tasks with addons. If `true`, returns only Tasks with addons. If `false`, returns only Tasks without addons.
     *
     * @param bool $hasAddons Whether to read Tasks with addons
     * @return $this Fluent Builder
     */
    public function setHasAddons(bool $hasAddons): self {
        $this->options['hasAddons'] = $hasAddons;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Taskrouter.V1.ReadTaskOptions ' . $options . ']';
    }
}

class CreateTaskOptions extends Options {
    /**
     * @param int $timeout The amount of time in seconds the task can live before
     *                     being assigned
     * @param int $priority The priority to assign the new task and override the
     *                      default
     * @param string $taskChannel When MultiTasking is enabled specify the
     *                            TaskChannel by passing either its unique_name or
     *                            SID
     * @param string $workflowSid The SID of the Workflow that you would like to
     *                            handle routing for the new Task
     * @param string $attributes A URL-encoded JSON string describing the
     *                           attributes of the task
     */
    public function __construct(int $timeout = Values::NONE, int $priority = Values::NONE, string $taskChannel = Values::NONE, string $workflowSid = Values::NONE, string $attributes = Values::NONE) {
        $this->options['timeout'] = $timeout;
        $this->options['priority'] = $priority;
        $this->options['taskChannel'] = $taskChannel;
        $this->options['workflowSid'] = $workflowSid;
        $this->options['attributes'] = $attributes;
    }

    /**
     * The amount of time in seconds the new task can live before being assigned. Can be up to a maximum of 2 weeks (1,209,600 seconds). The default value is 24 hours (86,400 seconds). On timeout, the `task.canceled` event will fire with description `Task TTL Exceeded`.
     *
     * @param int $timeout The amount of time in seconds the task can live before
     *                     being assigned
     * @return $this Fluent Builder
     */
    public function setTimeout(int $timeout): self {
        $this->options['timeout'] = $timeout;
        return $this;
    }

    /**
     * The priority to assign the new task and override the default. When supplied, the new Task will have this priority unless it matches a Workflow Target with a Priority set. When not supplied, the new Task will have the priority of the matching Workflow Target. Value can be 0 to 2^31^ (2,147,483,647).
     *
     * @param int $priority The priority to assign the new task and override the
     *                      default
     * @return $this Fluent Builder
     */
    public function setPriority(int $priority): self {
        $this->options['priority'] = $priority;
        return $this;
    }

    /**
     * When MultiTasking is enabled, specify the TaskChannel by passing either its `unique_name` or `sid`. Default value is `default`.
     *
     * @param string $taskChannel When MultiTasking is enabled specify the
     *                            TaskChannel by passing either its unique_name or
     *                            SID
     * @return $this Fluent Builder
     */
    public function setTaskChannel(string $taskChannel): self {
        $this->options['taskChannel'] = $taskChannel;
        return $this;
    }

    /**
     * The SID of the Workflow that you would like to handle routing for the new Task. If there is only one Workflow defined for the Workspace that you are posting the new task to, this parameter is optional.
     *
     * @param string $workflowSid The SID of the Workflow that you would like to
     *                            handle routing for the new Task
     * @return $this Fluent Builder
     */
    public function setWorkflowSid(string $workflowSid): self {
        $this->options['workflowSid'] = $workflowSid;
        return $this;
    }

    /**
     * A URL-encoded JSON string with the attributes of the new task. This value is passed to the Workflow's `assignment_callback_url` when the Task is assigned to a Worker. For example: `{ "task_type": "call", "twilio_call_sid": "CAxxx", "customer_ticket_number": "12345" }`.
     *
     * @param string $attributes A URL-encoded JSON string describing the
     *                           attributes of the task
     * @return $this Fluent Builder
     */
    public function setAttributes(string $attributes): self {
        $this->options['attributes'] = $attributes;
        return $this;
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $options = \http_build_query(Values::of($this->options), '', ' ');
        return '[Twilio.Taskrouter.V1.CreateTaskOptions ' . $options . ']';
    }
}