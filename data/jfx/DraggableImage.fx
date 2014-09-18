//package draggable;
import java.lang.Math;
import javafx.scene.*;
import javafx.scene.image.*;
import javafx.scene.input.*;
import javafx.scene.paint.*;
import javafx.scene.shape.*;
import javafx.scene.transform.*;
import javafx.stage.*;

public class DraggableImage extends CustomNode {
    public var image: Image;
    public var x: Number;
    public var y: Number;
    public var angle: Number;
    var startX: Number = 0.0;
    var startY: Number = 0.0;
    var startA: Number = 0.0;

    def mover:Circle = Circle {
      cursor: Cursor.CROSSHAIR,
      radius: 20,
      centerX: image.width / 2.0,
      centerY: image.height / 2.0,
      fill: Color.rgb(200, 200, 255),
      opacity: 0.0,
      onMouseMoved: function(e:MouseEvent):Void {
          mover.opacity = 0.5; rotator.opacity = 0.0;
      },
      onMousePressed: function(e:MouseEvent):Void {
          startX = e.sceneX; startY = e.sceneY;
      }
      onMouseDragged: function(e:MouseEvent):Void {
          mover.opacity = 0.5;
          x += e.sceneX - startX;
          y += e.sceneY - startY;
          startX = e.sceneX; startY = e.sceneY;
      }
    };
    def rotator:ShapeSubtract = ShapeSubtract {
      cursor: Cursor.HAND,
      fill: Color.rgb(200, 200, 255),
      opacity: 0.0,
      onMouseReleased: function(e:MouseEvent):Void {
          //mover.opacity = if(mover.hover) 0.5 else 0.0;
          rotator.opacity = if(rotator.hover) 0.5 else 0.0;
      },
      onMouseMoved: function(e:MouseEvent):Void {
          mover.opacity = 0.0; rotator.opacity = 0.5;
      },
      onMouseExited: function(e:MouseEvent):Void {
          rotator.opacity = 0.0;
      },
      onMousePressed: function(e:MouseEvent):Void {
          mover.opacity = 0.0; rotator.opacity = 0.5;
          var r = Math.toDegrees(Math.atan2(y+mover.centerY-e.sceneY,
                                            x+mover.centerX-e.sceneX));
          startA = angle - r;
      }
      onMouseDragged: function(e:MouseEvent):Void {
          mover.opacity = 0.0; rotator.opacity = 0.5;
          var r = Math.toDegrees(Math.atan2(y+mover.centerY-e.sceneY,
                                            x+mover.centerX-e.sceneX));
          angle = r + startA;
      }
      a: Circle {
        centerX: bind mover.centerX, centerY: bind mover.centerY,
        radius: bind mover.radius * 3.0,
      }
      b: Circle {
        centerX: bind mover.centerX, centerY: bind mover.centerY,
        radius: bind mover.radius,
      }
    };
    def border = Rectangle{
      width: image.width, height: image.height
      arcWidth: 20, arcHeight: 20,
    };
    override public function create(): Node {
        return Group {
          rotate:     bind angle,
          translateX: bind x,
          translateY: bind y,
          content: [
              ImageView { clip: border, image: image, smooth: true, cache: true },
              DelegateShape {
                fill: null, shape: border,
                stroke: Color.WHITE, strokeWidth: 4,
              },
              rotator, mover ]
        };
    }
}
function run() {
    Stage {
//      var screen = java.awt.Toolkit.getDefaultToolkit().getScreenSize();
//      width: screen.width, height: screen.height
//      style: StageStyle.TRANSPARENT
      title: "JavaFX - Image Mover Rotator"
      width: 640, height: 480
      scene: Scene {
        fill: Color.LIGHTGRAY
        content: [ DraggableImage {
          image: Image { url: "http://terai.xrea.jp/data/jfx/image.jpg" },
          x:100, y:100, angle:45
        }]
      }
      visible: true
    }
}
